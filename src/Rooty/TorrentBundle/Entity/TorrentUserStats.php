<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\TorrentBundle\Entity\TorrentUserStats
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TorrentUserStats
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
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\Torrent")
     */
    private $torrent;

    /**
     * @ORM\ManyToOne(targetEntity="\Rooty\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var integer $upload
     *
     * @ORM\Column(name="upload", type="integer")
     */
    private $upload;

    /**
     * @var integer $download
     *
     * @ORM\Column(name="download", type="integer")
     */
    private $download;

    /**
     * @var string $finished
     *
     * @ORM\Column(name="finished", type="string", length=255)
     */
    private $finished;


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
     * Set upload
     *
     * @param integer $upload
     */
    public function setUpload($upload)
    {
        $this->upload = $upload;
    }

    /**
     * Get upload
     *
     * @return integer 
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * Set download
     *
     * @param integer $download
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }

    /**
     * Get download
     *
     * @return integer 
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Set finished
     *
     * @param string $finished
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

    /**
     * Get finished
     *
     * @return string 
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set torrent
     *
     * @param Rooty\TorrentBundle\Entity\User $torrent
     */
    public function setTorrent(\Rooty\TorrentBundle\Entity\User $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * Get torrent
     *
     * @return Rooty\TorrentBundle\Entity\User 
     */
    public function getTorrent()
    {
        return $this->torrent;
    }

    /**
     * Set user
     *
     * @param Rooty\UserBundle\Entity\User $user
     */
    public function setUser(\Rooty\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}