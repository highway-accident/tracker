<?php

namespace Rooty\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\CommentBundle\Entity\Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity
 */
class Comment
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
    private $added_by;

    /**
     * @var datetime $date_added
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $date_added;

    /**
     * @var datetime $date_updated
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $date_updated;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;


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
     * Set date_added
     *
     * @param datetime $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;
    }

    /**
     * Get date_added
     *
     * @return datetime 
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set date_updated
     *
     * @param datetime $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->date_updated = $dateUpdated;
    }

    /**
     * Get date_updated
     *
     * @return datetime 
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    /**
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
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

    /**
     * Set added_by
     *
     * @param Rooty\UserBundle\Entity\User $addedBy
     */
    public function setAddedBy(\Rooty\UserBundle\Entity\User $addedBy)
    {
        $this->added_by = $addedBy;
    }

    /**
     * Get added_by
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getAddedBy()
    {
        return $this->added_by;
    }
}