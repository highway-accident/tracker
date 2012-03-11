<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rooty\TorrentBundle\Entity\MovieReleaseGroup
 *
 * @ORM\Table(name="movie_release_groups")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class MovieReleaseGroup
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
    * @Assert\File(maxSize="6000000")
    */
    private $image_file;

    /**
     * @var string $image_url
     *
     * @ORM\Column(name="image_url", type="string", length=255)
     */
    private $image_url;


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
     * Set image_file
     *
     * @param string $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->image_file = $imageFile;
    }

    /**
     * Get image_file
     *
     * @return string 
     */
    public function getImageFile()
    {
        return $this->image_file;
    }

    /**
     * Set image_url
     *
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->image_url = $imageUrl;
    }

    /**
     * Get image_url
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }
    
    public function getImageAbsolutePath()
    {
        return null === $this->image_url ? null : $this->getImageUploadRootDir().'/'.$this->image_url;
    }

    public function getImageWebPath()
    {
        return null === $this->image_url ? null : $this->getImageUploadDir().'/'.$this->image_url;
    }

    protected function getImageUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getImageUploadDir();
    }

    protected function getImageUploadDir()
    {
        return 'uploads/release_groups';
    }
    
    /**
    /* @ORM\PrePersist()
    /* @ORM\PreUpdate()
    */
    public function preUpload()
    {
        if (null !== $this->image_file) {
            $this->image_url = uniqid().'.'.$this->image_file->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->image_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->image_file->move($this->getImageUploadRootDir(), $this->image_url);
        
        unset($this->image_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($image_file = $this->getImageAbsolutePath()) {
            unlink($image_file);
        }
    }
}