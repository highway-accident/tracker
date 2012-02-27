<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\TorrentBundle\Entity\Peer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Peer
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $info_hash
     *
     * @ORM\Column(name="info_hash", type="string", length=40)
     */
    private $info_hash;

    /**
     * @var string $peer_id
     *
     * @ORM\Column(name="peer_id", type="string", length=40)
     */
    private $peer_id;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=16)
     */
    private $ip;

    /**
     * @var integer $port
     *
     * @ORM\Column(name="port", type="integer")
     */
    private $port;

    /**
     * @var integer $uploaded
     *
     * @ORM\Column(name="uploaded", type="integer")
     */
    private $uploaded;

    /**
     * @var integer $downloaded
     *
     * @ORM\Column(name="downloaded", type="integer")
     */
    private $downloaded;

    /**
     * @var boolean $complete
     *
     * @ORM\Column(name="complete", type="boolean")
     */
    private $complete;

    /**
     * @var datetime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set info_hash
     *
     * @param string $infoHash
     */
    public function setInfoHash($infoHash)
    {
        $this->info_hash = $infoHash;
    }

    /**
     * Get info_hash
     *
     * @return string 
     */
    public function getInfoHash()
    {
        return $this->info_hash;
    }

    /**
     * Set peer_id
     *
     * @param string $peerId
     */
    public function setPeerId($peerId)
    {
        $this->peer_id = $peerId;
    }

    /**
     * Get peer_id
     *
     * @return string 
     */
    public function getPeerId()
    {
        return $this->peer_id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set port
     *
     * @param integer $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set uploaded
     *
     * @param integer $uploaded
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
    }

    /**
     * Get uploaded
     *
     * @return integer 
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set downloaded
     *
     * @param integer $downloaded
     */
    public function setDownloaded($downloaded)
    {
        $this->downloaded = $downloaded;
    }

    /**
     * Get downloaded
     *
     * @return integer 
     */
    public function getDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * Set complete
     *
     * @param boolean $complete
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }

    /**
     * Get complete
     *
     * @return boolean 
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set timestamp
     *
     * @param datetime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get timestamp
     *
     * @return datetime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}