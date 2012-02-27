<?php

require 'request.php';
require 'response.php';

class Announcer
{
    private $request;
    private $response;
    private $dbh;
    private $torrent_id;
    private $user_id;
    private $gold = false;
    
    public function __construct($request)
    {
        $this->request = $request;
        
        $user = 'tracker';
        $pass = 'DfyEScTndhDMb53Y';
        try {
            $this->dbh = new PDO('mysql:host=localhost;dbname=tracker', $user, $pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        $this->validateRequest();
    }
    
    public function processRequest()
    {
        switch ($this->request->getEvent()) {
            case 'started':
                $sql = 'INSERT INTO peers (info_hash, peer_id, ip, port, complete) VALUES (:info_hash, :peer_id, :ip, :port, :complete)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':info_hash', $this->request->getInfoHash());
                $stmt->bindParam(':peer_id', $this->request->getPeerId());
                $stmt->bindParam(':ip', $this->request->getIp());
                $stmt->bindParam(':port', $this->request->getPort());
                $stmt->bindValue(':complete', ($this->request->getLeft()) ? 0 : 1);
                if (!$stmt->execute()) {
                    throw new Exception('DB error.');
                }
                
                $sql = 'INSERT IGNORE INTO user_torrents (torrent_id, user_id) VALUES (:torrent_id, :user_id)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':torrent_id', $this->torrent_id);
                $stmt->bindParam(':user_id', $this->user_id);
                if (!$stmt->execute()) {
                    throw new Exception('DB error.');
                }
                break;
            case 'stopped':
                $this->updateStats();
                $sql = "DELETE FROM peers WHERE info_hash = :info_hash AND peer_id = :peer_id";
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':info_hash', $this->request->getInfoHash());
                $stmt->bindParam(':peer_id', $this->request->getPeerId());
                if (!$stmt->execute()) {
                    throw new Exception('DB error.');
                }
                break;
            case 'completed':
                $this->updateStats();
                $sql = "UPDATE peers SET complete = 1, timestamp = NOW() WHERE info_hash = :info_hash AND peer_id = :peer_id";
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':info_hash', $this->request->getInfoHash());
                $stmt->bindParam(':peer_id', $this->request->getPeerId());
                if (!$stmt->execute()) {
                    throw new Exception('DB error.');
                }
                
                // Update per torrent stats
                $sql = 'UPDATE user_torrents SET finished = 1 WHERE torrent_id = :torrent_id AND user_id = :user_id';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':torrent_id', $this->torrent_id);
                $stmt->bindParam(':user_id', $this->user_id);
                if (!$stmt->execute()) {
                    throw new Exception('DB error.');
                }
                break;
            default:
                $this->updateStats();
                break;
        }
    }
    
    public function sendResponse()
    {
        $this->response = new Response();
        
        $interval = 60*15; //15 min
        $peers = array();
        $complete = 0;
        $incomplete = 0;
        
        $sql = "SELECT peer_id, ip, port FROM peers WHERE info_hash = :info_hash";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $peers[] = array('peer id' => $row['peer_id'],
                                 'ip' => $row['ip'],
                                 'port' => (int)$row['port'],
                );
            }
        } else {
            throw new Exception('DB error.');
        }
        
        $sql = "SELECT COUNT(*) AS complete FROM peers WHERE info_hash = :info_hash AND complete = 1";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $complete = (int)$row['complete'];
        } else {
            throw new Exception('DB error.');
        }
        
        $sql = "SELECT COUNT(*) AS incomplete FROM peers WHERE info_hash = :info_hash AND complete = 0";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $incomplete = (int)$row['incomplete'];
        } else {
            throw new Exception('DB error.');
        }
        
        $this->response->setInterval($interval);
        $this->response->setPeers($peers);
        $this->response->setComplete($complete);
        $this->response->setIncomplete($incomplete);
        
        $this->response->send();
    }
    
    private function validateRequest()
    {
        if ($this->request->getUploaded() < 0 || $this->request->getDownloaded() < 0) {
            throw new Exception("Wrong request format: negative values are not allowed.");
        }
        
        // Check if such torrent exists
        $sql = 'SELECT id FROM torrents WHERE info_hash = :info_hash';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $this->torrent_id = $row['id'];
        } else {
            throw new Exception('We don\'t know anything about this torrent.');
            //throw new Exception($this->request->getInfoHash());
        }
        
        // Check if such user exists
        $sql = 'SELECT id FROM users WHERE passkey = :passkey';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':passkey', $this->request->getPasskey());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $this->user_id = $row['id'];
        } else {
            throw new Exception('Invalid passkey.');
        }
        
        // Check if user has gold role
        $sql = 'SELECT roles FROM users WHERE passkey = :passkey';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':passkey', $this->request->getPasskey());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $this->gold = in_array('USER_GOLD', unserialize($row['roles']));
        } else {
            throw new Exception('DB error.');
        }
        
        // Make double start the usual timer request
        if ($this->request->getEvent() == 'started') {
            $sql = "SELECT id FROM peers WHERE info_hash = :info_hash AND peer_id = :peer_id AND ip = :ip";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':info_hash', $this->request->getInfoHash());
            $stmt->bindParam(':peer_id', $this->request->getPeerId());
            $stmt->bindParam(':ip', $this->request->getIp());
            if ($stmt->execute()) {
                if ($row = $stmt->fetch()) {
                    $this->request->setEvent(NULL);
                }
            } else {
                throw new Exception('DB error.');
            }
        }
    }
    
    private function updateStats()
    {
        $sql = 'SELECT uploaded, downloaded FROM peers WHERE info_hash = :info_hash AND peer_id = :peer_id AND ip = :ip';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        $stmt->bindParam(':peer_id', $this->request->getPeerId());
        $stmt->bindParam(':ip', $this->request->getIp());
        if ($stmt->execute() && $row = $stmt->fetch()) {
            $uploadDiff = $this->request->getUploaded() - $row['uploaded'];
            $downloadDiff = $this->request->getDownloaded() - $row['uploaded'];
        } else {
            throw new Exception('DB error.');
        }
        
        // Update peers table stats
        $sql = "UPDATE peers SET uploaded = :uploaded, downloaded = :downloaded, timestamp = NOW() WHERE info_hash = :info_hash AND peer_id = :peer_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':uploaded', $this->request->getUploaded());
        $stmt->bindValue(':downloaded', $this->request->getDownloaded());
        $stmt->bindParam(':info_hash', $this->request->getInfoHash());
        $stmt->bindParam(':peer_id', $this->request->getPeerId());
        if (!$stmt->execute()) {
            throw new Exception('DB error.');
        }
        
        // Update per torrent stats
        $sql = 'UPDATE user_torrents SET uploaded = uploaded + :uploadDiff, downloaded = downloaded + :downloadDiff WHERE torrent_id = :torrent_id AND user_id = :user_id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':uploadDiff', $this->request->getUploaded());
        $stmt->bindValue(':downloadDiff', ($this->gold) ? 0 : $downloadDiff);
        $stmt->bindParam(':torrent_id', $this->torrent_id);
        $stmt->bindParam(':user_id', $this->user_id);
        if (!$stmt->execute()) {
            throw new Exception('DB error.');
        }
        
        // Update overall user stats
        $sql = 'UPDATE users SET uploaded = uploaded + :uploadDiff, downloaded = downloaded + :downloadDiff WHERE id = :id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':uploadDiff', $this->request->getUploaded());
        $stmt->bindValue(':downloadDiff', ($this->gold) ? 0 : $downloadDiff);
        $stmt->bindParam(':id', $this->user_id);
        if (!$stmt->execute()) {
            throw new Exception('DB error.');
        }
    }
}

try {
    $request = new Request();
    $announcer = new Announcer($request);
    $announcer->processRequest();
    $announcer->sendResponse();
} catch (Exceptiйon $e) {
    $response = new Response();
    $response->setFailureReason($e->getMessage());
    $response->send();
}