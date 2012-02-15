<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\TorrentBundle\Entity\Vote
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity
 */
class Vote
{
    const TYPE_LIKE = 'like';
    const TYPE_DISLIKE = 'dislike';
    
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_LIKE, self::TYPE_DISLIKE))) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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