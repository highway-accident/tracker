<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\TorrentBundle\Entity\TorrentUserStats
 *
 * @ORM\Table(name="user_torrents", indexes={@ORM\Index(name="search_idx", columns={"torrent_id", "user_id"})})
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
     * @var boolean $finished
     *
     * @ORM\Column(name="finished", type="boolean")
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
     * Set finished
     *
     * @param boolean $finished
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

    /**
     * Get finished
     *
     * @return boolean 
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set torrent
     *
     * @param Rooty\TorrentBundle\Entity\Torrent $torrent
     */
    public function setTorrent(\Rooty\TorrentBundle\Entity\Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * Get torrent
     *
     * @return Rooty\TorrentBundle\Entity\Torrent 
     */
    public function getTorrent()
    {
        return $this->torrent;
    }
}