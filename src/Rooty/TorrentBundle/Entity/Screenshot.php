<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rooty\TorrentBundle\Entity\Screenshot
 *
 * @ORM\Table(name="screenshots")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Screenshot
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
     * @ORM\ManyToOne(targetEntity="Torrent")
     * @ORM\JoinColumn(name="torrent_id", referencedColumnName="id")
     */
    private $torrent;
    
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $screenshot_url
     *
     * @ORM\Column(name="screenshot_url", type="string", length=255)
     */
    private $screenshot_url;

    /**
    * @Assert\File(maxSize="6000000")
    */
    private $screenshot_file;
    
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
     * Set screenshot_url
     *
     * @param string $screenshotUrl
     */
    public function setScreenshotUrl($screenshotUrl)
    {
        $this->screenshot_url = $screenshotUrl;
    }

    /**
     * Get screenshot_url
     *
     * @return string 
     */
    public function getScreenshotUrl()
    {
        return $this->screenshot_url;
    }
    
    public function getScreenshotAbsolutePath()
    {
        return null === $this->screenshot_url ? null : $this->getScreenshotUploadRootDir().'/'.$this->screenshot_url;
    }

    public function getScreenshotWebPath()
    {
        return null === $this->screenshot_url ? null : $this->getScreenshotUploadDir().'/'.$this->screenshot_url;
    }

    protected function getScreenshotUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getScreenshotUploadDir();
    }

    protected function getScreenshotUploadDir()
    {
        return 'uploads/screenshots';
    }
    
    /**
    /* @ORM\PrePersist()
    /* @ORM\PreUpdate()
    */
    public function preUpload()
    {
        if (null !== $this->screenshot_file) {
            $this->screenshot_url = uniqid().'.'.$this->screenshot_file->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->screenshot_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->screenshot_file->move($this->getScreenshotUploadRootDir(), $this->screenshot_url);
        
        unset($this->screenshot_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($screenshot_file = $this->getScreenshotAbsolutePath()) {
            unlink($screenshot_file);
        }
    }
    
    public function setScreenshotFile($screenshotFile)
    {
        $this->screenshot_file = $screenshotFile;
    }
    
    public function getScreenshotFile()
    {
        return $this->screenshot_file;
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