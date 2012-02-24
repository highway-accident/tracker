<?php

namespace Rooty\IMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\PMBundle\Entity\Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="Rooty\UserBundle\Entity\User")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="Rooty\UserBundle\Entity\User")
     */
    private $recepient;

    /**
     * @var datetime $dateAdded
     *
     * @ORM\Column(name="dateAdded", type="datetime")
     */
    private $dateAdded;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @var boolean $unread
     *
     * @ORM\Column(name="unread", type="boolean")
     */
    private $unread = true;

    /**
     * @var boolean $notified
     *
     * @ORM\Column(name="notified", type="boolean")
     */
    private $notified = false;
    
    /**
     * @var boolean $deletedBySender
     *
     * @ORM\Column(name="deletedBySender", type="boolean")
     */
    private $deletedBySender = false;
    
    /**
     * @var boolean $deletedByRecepient
     *
     * @ORM\Column(name="deletedByRecepient", type="boolean")
     */
    private $deletedByRecepient = false;


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
     * Set dateAdded
     *
     * @param datetime $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * Get dateAdded
     *
     * @return datetime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
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
     * Set unread
     *
     * @param boolean $unread
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;
    }

    /**
     * Get unread
     *
     * @return boolean 
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * Set sender
     *
     * @param Rooty\UserBundle\Entity\User $sender
     */
    public function setSender(\Rooty\UserBundle\Entity\User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get sender
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set recepient
     *
     * @param Rooty\UserBundle\Entity\User $recepient
     */
    public function setRecepient(\Rooty\UserBundle\Entity\User $recepient)
    {
        $this->recepient = $recepient;
    }

    /**
     * Get recepient
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getRecepient()
    {
        return $this->recepient;
    }

    /**
     * Set notified
     *
     * @param boolean $notified
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;
    }

    /**
     * Get notified
     *
     * @return boolean 
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Set deletedBySender
     *
     * @param boolean $deletedBySender
     */
    public function setDeletedBySender($deletedBySender)
    {
        $this->deletedBySender = $deletedBySender;
    }

    /**
     * Get deletedBySender
     *
     * @return boolean 
     */
    public function getDeletedBySender()
    {
        return $this->deletedBySender;
    }

    /**
     * Set deletedByRecepient
     *
     * @param boolean $deletedByRecepient
     */
    public function setDeletedByRecepient($deletedByRecepient)
    {
        $this->deletedByRecepient = $deletedByRecepient;
    }

    /**
     * Get deletedByRecepient
     *
     * @return boolean 
     */
    public function getDeletedByRecepient()
    {
        return $this->deletedByRecepient;
    }
}