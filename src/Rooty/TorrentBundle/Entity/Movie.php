<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rooty\TorrentBundle\Entity\Movie
 *
 * @ORM\Table(name="movies")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Movie
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
     * @ORM\OneToOne(targetEntity="Torrent", cascade={"persist", "remove"})
     */
    private $torrent;
    
    /**
     * @var string $genre
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @var string $director
     *
     * @ORM\Column(name="director", type="string", length=255)
     */
    private $director;

    /**
     * @var text $cast
     *
     * @ORM\Column(name="cast", type="text")
     */
    private $cast;

    /**
     * @var string $country
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string $studio
     *
     * @ORM\Column(name="studio", type="string", length=255)
     */
    private $studio;

    /**
     * @var string $length
     *
     * @ORM\Column(name="length", type="string", length=255)
     */
    private $length;

    /**
     * @var string $imdb_id
     *
     * @ORM\Column(name="imdb_id", type="string", length=255)
     */
    private $imdb_id;

    /**
     * @var string $kinopoisk_id
     *
     * @ORM\Column(name="kinopoisk_id", type="string", length=255)
     */
    private $kinopoisk_id;

    /**
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\MovieTranslation")
     */
    private $translation;

    /**
     * @var string $subtitles
     *
     * @ORM\Column(name="subtitles", type="string", length=255)
     */
    private $subtitles;

    /**
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\MovieFormat")
     */
    private $format;

    /**
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\MovieQuality")
     */
    private $quality;

    /**
     * @var string $video
     *
     * @ORM\Column(name="video", type="string", length=255)
     */
    private $video;

    /**
     * @var string $audio
     *
     * @ORM\Column(name="audio", type="string", length=255)
     */
    private $audio;

    /**
    * @Assert\File(maxSize="6000000")
    */
    private $sample_file;
    
    /**
     * @var string $sample_url
     *
     * @ORM\Column(name="sample_url", type="string", length=255)
     */
    private $sample_url;

    /**
     * @ORM\ManyToOne(targetEntity="\Rooty\TorrentBundle\Entity\MovieReleaseGroup")
     */
    private $release_group;


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
     * Set genre
     *
     * @param string $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set director
     *
     * @param string $director
     */
    public function setDirector($director)
    {
        $this->director = $director;
    }

    /**
     * Get director
     *
     * @return string 
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set cast
     *
     * @param text $cast
     */
    public function setCast($cast)
    {
        $this->cast = $cast;
    }

    /**
     * Get cast
     *
     * @return text 
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set studio
     *
     * @param string $studio
     */
    public function setStudio($studio)
    {
        $this->studio = $studio;
    }

    /**
     * Get studio
     *
     * @return string 
     */
    public function getStudio()
    {
        return $this->studio;
    }

    /**
     * Set length
     *
     * @param string $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Get length
     *
     * @return string 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set imdb_id
     *
     * @param string $imdbId
     */
    public function setImdbId($imdbId)
    {
        $this->imdb_id = $imdbId;
    }

    /**
     * Get imdb_id
     *
     * @return string 
     */
    public function getImdbId()
    {
        return $this->imdb_id;
    }

    /**
     * Set kinopoisk_id
     *
     * @param string $kinopoiskId
     */
    public function setKinopoiskId($kinopoiskId)
    {
        $this->kinopoisk_id = $kinopoiskId;
    }

    /**
     * Get kinopoisk_id
     *
     * @return string 
     */
    public function getKinopoiskId()
    {
        return $this->kinopoisk_id;
    }

    /**
     * Set subtitles
     *
     * @param string $subtitles
     */
    public function setSubtitles($subtitles)
    {
        $this->subtitles = $subtitles;
    }

    /**
     * Get subtitles
     *
     * @return string 
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * Set video
     *
     * @param string $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set audio
     *
     * @param string $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }

    /**
     * Get audio
     *
     * @return string 
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Set sample_file
     *
     * @param string $sampleFile
     */
    public function setSampleFile($sampleFile)
    {
        $this->sample_file = $sampleFile;
    }

    /**
     * Get sample_file
     *
     * @return string 
     */
    public function getSampleFile()
    {
        return $this->sample_file;
    }
    
    /**
     * Set sample_url
     *
     * @param string $sampleUrl
     */
    public function setSampleUrl($sampleUrl)
    {
        $this->sample_url = $sampleUrl;
    }

    /**
     * Get sample_url
     *
     * @return string 
     */
    public function getSampleUrl()
    {
        return $this->sample_url;
    }
    
    public function getSampleAbsolutePath()
    {
        return null === $this->sample_url ? null : $this->getSampleUploadRootDir().'/'.$this->sample_url;
    }

    public function getSampleWebPath()
    {
        return null === $this->sample_url ? null : $this->getSampleUploadDir().'/'.$this->sample_url;
    }

    protected function getSampleUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getSampleUploadDir();
    }

    protected function getSampleUploadDir()
    {
        return 'uploads/samples';
    }
    
    /**
    /* @ORM\PrePersist()
    /* @ORM\PreUpdate()
    */
    public function preUpload()
    {
        if (null !== $this->sample_file) {
            $this->sample_url = uniqid().'.'.$this->sample_file->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->sample_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->sample_file->move($this->getSampleUploadRootDir(), $this->sample_url);
        
        unset($this->sample_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($sample_file = $this->getSampleAbsolutePath()) {
            unlink($sample_file);
        }
    }

    /**
     * Set translation
     *
     * @param Rooty\TorrentBundle\Entity\MovieTranslation $translation
     */
    public function setTranslation(\Rooty\TorrentBundle\Entity\MovieTranslation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * Get translation
     *
     * @return Rooty\TorrentBundle\Entity\MovieTranslation 
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set format
     *
     * @param Rooty\TorrentBundle\Entity\MovieFormat $format
     */
    public function setFormat(\Rooty\TorrentBundle\Entity\MovieFormat $format)
    {
        $this->format = $format;
    }

    /**
     * Get format
     *
     * @return Rooty\TorrentBundle\Entity\MovieFormat 
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set quality
     *
     * @param Rooty\TorrentBundle\Entity\MovieQuality $quality
     */
    public function setQuality(\Rooty\TorrentBundle\Entity\MovieQuality $quality)
    {
        $this->quality = $quality;
    }

    /**
     * Get quality
     *
     * @return Rooty\TorrentBundle\Entity\MovieQuality 
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set release_group
     *
     * @param Rooty\TorrentBundle\Entity\MovieReleaseGroup $releaseGroup
     */
    public function setReleaseGroup(\Rooty\TorrentBundle\Entity\MovieReleaseGroup $releaseGroup)
    {
        $this->release_group = $releaseGroup;
    }

    /**
     * Get release_group
     *
     * @return Rooty\TorrentBundle\Entity\MovieReleaseGroup 
     */
    public function getReleaseGroup()
    {
        return $this->release_group;
    }
}