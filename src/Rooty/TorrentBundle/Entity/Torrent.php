<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Rooty\TorrentBundle\Entity\Torrent
 *
 * @ORM\Table(name="torrents")
 * @ORM\Entity(repositoryClass="\Rooty\TorrentBundle\Entity\Repository\TorrentRepository")
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
     * @var string $info_hash
     *
     * @ORM\Column(name="info_hash", type="string", length=255)
     */
    private $info_hash;
    
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
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\Type")
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

    private $poster_file;
    
    /**
     * @var string $torrent_url
     *
     * @ORM\Column(name="torrent_url", type="string", length=255)
     */
    private $torrent_url;
    
    private $torrent_file;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Screenshot", mappedBy="torrent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $screenshots;

    /**
     * @var integer $discount
     *
     * @ORM\Column(name="discount", type="integer")
     */
    private $discount = 0;
    
    /**
     * @var integer $views
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views = 0;

    /**
     * @var integer $downloads
     *
     * @ORM\Column(name="downloads", type="integer")
     */
    private $downloads = 0;
    
    /**
     * @var boolean $is_sticky
     *
     * @ORM\Column(name="is_sticky", type="boolean")
     */
    private $is_sticky = false;
    
    /**
     * @var string $check_status
     *
     * @ORM\Column(name="check_status", type="string", length=255)
     */
    private $check_status = 'unchecked';

     /**
     * @ORM\ManyToOne(targetEntity="\Rooty\UserBundle\Entity\User")
     */
    private $checked_by;

    public function __construct()
    {
        $this->screenshots = new ArrayCollection();
    }
    
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
        return null === $this->torrent_url ? null : $this->getTorrentUploadRootDir().'/'.$this->torrent_url;
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
            $this->torrent_url = uniqid().'.torrent';
            
            // set torrent file size
            $this->size = filesize($this->torrent_file);
            $torrent = new \Torrent_Torrent($this->torrent_file);
            $this->info_hash = $torrent->hash_info();
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

    /**
     * Add screenshots
     *
     * @param Rooty\TorrentBundle\Entity\Screenshot $screenshots
     */
    public function addScreenshot(\Rooty\TorrentBundle\Entity\Screenshot $screenshots)
    {
        $this->screenshots[] = $screenshots;
        $screenshots->setTorrent($this);
    }
    
    /**
     * Remove screenshots
     *
     * @param Rooty\TorrentBundle\Entity\Screenshot $screenshots
     */
    public function removeScreenshot(\Rooty\TorrentBundle\Entity\Screenshot $screenshots)
    {
        $this->screenshots->removeElement($screenshots);
    }
    
    public function setScreenshots($screenshots)
    {
        $this->screenshots = new ArrayCollection();
        foreach ($screenshots as $screenshot) {
            $this->addScreenshot($screenshot);
        }
    }

    /**
     * Get screenshots
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getScreenshots()
    {
        return $this->screenshots;
    }

    /**
     * Set type
     *
     * @param Rooty\TorrentBundle\Entity\Type $type
     */
    public function setType(\Rooty\TorrentBundle\Entity\Type $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return Rooty\TorrentBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set is_sticky
     *
     * @param boolean $isSticky
     */
    public function setIsSticky($isSticky)
    {
        $this->is_sticky = $isSticky;
    }

    /**
     * Get is_sticky
     *
     * @return boolean 
     */
    public function getIsSticky()
    {
        return $this->is_sticky;
    }

    /**
     * Set checked_by
     *
     * @param Rooty\UserBundle\Entity\User $checkedBy
     */
    public function setCheckedBy(\Rooty\UserBundle\Entity\User $checkedBy)
    {
        /* If already checked do not override. Unset using setIsChecked */
        if (!$this->checked_by) {
            $this->checked_by = $checkedBy;
        }
    }

    /**
     * Get checked_by
     *
     * @return Rooty\UserBundle\Entity\User 
     */
    public function getCheckedBy()
    {
        return $this->checked_by;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set downloads
     *
     * @param integer $downloads
     */
    public function setDownloads($downloads)
    {
        $this->downloads = $downloads;
    }

    /**
     * Get downloads
     *
     * @return integer 
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    /**
     * Set check_status
     *
     * @param string $checkStatus
     */
    public function setCheckStatus($checkStatus)
    {
        if ($checkStatus == 'unchecked') {
            $this->checked_by = null;
        }
        
        $this->check_status = $checkStatus;
    }

    /**
     * Get check_status
     *
     * @return string 
     */
    public function getCheckStatus()
    {
        return $this->check_status;
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
}