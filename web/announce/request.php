<?php

class Request
{
    /* For field description read http://wiki.theory.org/BitTorrentSpecification#Tracker_Request_Parameters */
    private $passkey;
    private $info_hash;
    private $peer_id;
    private $ip;
    private $port;
    private $uploaded;
    private $downloaded;
    private $left;
    private $event;
    private $numwant = 50;

    public function __construct()
    {
        $required = array('passkey', 'info_hash', 'peer_id', 'port', 'uploaded', 'downloaded');
        
        foreach (get_object_vars($this) as $field => $value) {
            if (isset($_GET[$field]) && $_GET[$field] != NULL) {
                $this->$field = $_GET[$field];       
            } elseif (in_array($field, $required)) {
                throw new Exception("Wrong request format: missing $field field.");
            }
        }
        $this->port = (int)$this->port;
        $this->info_hash = bin2hex($this->info_hash);
        $this->peer_id = bin2hex($this->peer_id);
        
        file_put_contents('../uploads/log.txt', $_SERVER['QUERY_STRING'].'          /n', FILE_APPEND);
        
        $this->ip = $_SERVER['REMOTE_ADDR']; // We don't trust the value which came from the streets
    }
    
    public function getEvent()
    {
        return $this->event;
    }
    
    public function setEvent($event)
    {
        return $this->event = $event;
    }
    
    public function getPasskey()
    {
        return $this->passkey;
    }
    
    public function getInfoHash()
    {
        return $this->info_hash;
    }
    
    public function getPeerId()
    {
        return $this->peer_id;
    }
    
    public function getIp()
    {
        return $this->ip;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function getUploaded()
    {
        return $this->uploaded;
    }
    
    public function getDownloaded()
    {
        return $this->downloaded;
    }
    
    public function getLeft()
    {
        return $this->left;
    }
}