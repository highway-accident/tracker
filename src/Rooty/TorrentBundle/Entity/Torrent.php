<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rooty\TorrentBundle\Entity\Torrent
 *
 * @ORM\Table(name="torrents")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Torrent
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $title_original
     *
     * @ORM\Column(name="title_original", type="string", length=255, nullable=true)
     */
    private $title_original;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

     /**
     * @ORM\ManyToOne(targetEntity="\Rooty\UserBundle\Entity\User")
     */
    private $added_by;

    /**
     * @var integer $size
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

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
     * @var string $poster_url
     *
     * @ORM\Column(name="poster_url", type="string", length=255)
     */
    private $poster_url;

    /**
    * @Assert\File(maxSize="6000000")
    */
    private $poster_file;
    
    /**
     * @var string $torrent_url
     *
     * @ORM\Column(name="torrent_url", type="string", length=255)
     */
    private $torrent_url;

    /**
    * @Assert\File(maxSize="6000000")
    */
    private $torrent_file;

    /**
     * @var integer $views
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views = 0;

    /**
     * @var integer $hits
     *
     * @ORM\Column(name="hits", type="integer")
     */
    private $hits = 0;

    /**
     * @var integer $times_completed
     *
     * @ORM\Column(name="times_completed", type="integer")
     */
    private $times_completed = 0;

    /**
     * @var boolean $is_visible
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $is_visible = false;

    /**
     * @var boolean $is_blocked
     *
     * @ORM\Column(name="is_blocked", type="boolean")
     */
    private $is_blocked = false;

     /**
     * @ORM\OneToOne(targetEntity="\Rooty\UserBundle\Entity\User")
     */
    private $moderated_by;

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title_original
     *
     * @param string $titleOriginal
     */
    public function setTitleOriginal($titleOriginal)
    {
        $this->title_original = $titleOriginal;
    }

    /**
     * Get title_original
     *
\     * @return string 
     */
    public function getTitleOriginal()
    {
        return $this->title_original;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set size
     *
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
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
     * Set torrent_url
     *
     * @param string $torrentUrl
     */
    public function setTorrentUrl($torrentUrl)
    {
        $this->torrent_url = $torrentUrl;
    }

    /**
     * Get torrent_url
     *
     * @return string 
     */
    public function getTorrentUrl()
    {
        return $this->torrent_url;
    }

    public function getTorrentAbsolutePath()
    {
        return null === $this->torrent_url ? null : $this->getTorrentsUploadRootDir().'/'.$this->torrent_url;
    }

    public function getTorrentWebPath()
    {
        return null === $this->torrent_url ? null : $this->getTorrentUploadDir().'/'.$this->torrent_url;
    }

    protected function getTorrentUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getTorrentUploadDir();
    }

    protected function getTorrentUploadDir()
    {
        return 'uploads/torrents';
    }
    
    /**
     * Set poster_url
     *
     * @param string $posterUrl
     */
    public function setPosterUrl($posterUrl)
    {
        $this->poster_url = $posterUrl;
    }

    /**
     * Get poster_url
     *
     * @return string 
     */
    public function getPosterUrl()
    {
        return $this->poster_url;
    }
    
    public function getPosterAbsolutePath()
    {
        return null === $this->poster_url ? null : $this->getPosterUploadRootDir().'/'.$this->poster_url;
    }

    public function getPosterWebPath()
    {
        return null === $this->poster_url ? null : $this->getPosterUploadDir().'/'.$this->poster_url;
    }

    protected function getPosterUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getPosterUploadDir();
    }

    protected function getPosterUploadDir()
    {
        return 'uploads/posters';
    }


    /**
    /* @ORM\PrePersist()
    /* @ORM\PreUpdate()
    */
    public function preUpload()
    {
        //@todo filetype validation
        if (null !== $this->torrent_file) {
            $this->torrent_url = uniqid().'.'.$this->torrent_file->guessExtension();
            
            // set torrent file size
            $this->size = filesize($this->torrent_file);
        }
        
        if (null !== $this->poster_file) {
            $this->poster_url = uniqid().'.'.$this->poster_file->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->torrent_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->torrent_file->move($this->getTorrentUploadRootDir(), $this->torrent_url);
        
        unset($this->torrent_file);
        
        if (null === $this->poster_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->poster_file->move($this->getPosterUploadRootDir(), $this->poster_url);
        
        unset($this->poster_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($torrent_file = $this->getTorrentAbsolutePath()) {
            unlink($torrent_file);
        }
        
        if ($poster_file = $this->getPosterAbsolutePath()) {
            unlink($poster_file);
        }
    }
    
    /**
     * Set views
     *
     * @param integer $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     */
    public function setHits($hits)
    {
        $this->hits = $hits;
    }

    /**
     * Get hits
     *
     * @return integer 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set times_completed
     *
     * @param integer $timesCompleted
     */
    public function setTimesCompleted($timesCompleted)
    {
        $this->times_completed = $timesCompleted;
    }

    /**
     * Get times_completed
     *
     * @return integer 
     */
    public function getTimesCompleted()
    {
        return $this->times_completed;
    }

    /**
     * Set is_visible
     *
     * @param boolean $isVisible
     */
    public function setIsVisible($isVisible)
    {
        $this->is_visible = $isVisible;
    }

    /**
     * Get is_visible
     *
     * @return boolean 
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Set is_blocked
     *
     * @param boolean $isBlocked
     */
    public function setIsBlocked($isBlocked)
    {
        $this->is_blocked = $isBlocked;
    }

    /**
     * Get is_blocked
     *
     * @return boolean 
     */
    public function getIsBlocked()
    {
        return $this->is_blocked;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
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

    /**
     * Set moderated_by
     *
     * @param Rooty\UserBundle\Entity\User $moderatedBy
     */
    public function setModeratedBy(\Rooty\UserBundle\Entity\User $moderatedBy)
    {
        $this->moderated_by = $moderatedBy;
    }

    /**
     * Get moderated_by
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getModeratedBy()
    {
        return $this->moderated_by;
    }
    
    public function setTorrentFile($torrentFile)
    {
        $this->torrent_file = $torrentFile;
    }
    
    public function getTorrentFile()
    {
        return $this->torrent_file;
    }
    
    public function setPosterFile($posterFile)
    {
        $this->poster_file = $posterFile;
    }
    
    public function getPosterFile()
    {
        return $this->poster_file;
    }
}
