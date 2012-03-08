<?php

namespace Rooty\TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooty\TorrentBundle\Entity\Game
 *
 * @ORM\Table(name="games")
 * @ORM\Entity
 */
class Game
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
     * @var string $developer
     *
     * @ORM\Column(name="developer", type="string", length=255)
     */
    private $developer;

    /**
     * @var string $publisher
     *
     * @ORM\Column(name="publisher", type="string", length=255)
     */
    private $publisher;
    
    /**
     * @var text $system_requirements
     *
     * @ORM\Column(name="system_requirements", type="text")
     */
    private $system_requirements;

    /**
     * @var string $crack
     *
     * @ORM\Column(name="crack", type="string", length=255, nullable=true)
     */
    private $crack;

    /**
     * @var text $how_to_run
     *
     * @ORM\Column(name="how_to_run", type="text", nullable=true)
     */
    private $how_to_run;


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
     * Set developer
     *
     * @param string $developer
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;
    }

    /**
     * Get developer
     *
     * @return string 
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * Get publisher
     *
     * @return string 
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set system_requirements
     *
     * @param text $systemRequirements
     */
    public function setSystemRequirements($systemRequirements)
    {
        $this->system_requirements = $systemRequirements;
    }

    /**
     * Get system_requirements
     *
     * @return text 
     */
    public function getSystemRequirements()
    {
        return $this->system_requirements;
    }

    /**
     * Set how_to_run
     *
     * @param text $howToRun
     */
    public function setHowToRun($howToRun)
    {
        $this->how_to_run = $howToRun;
    }

    /**
     * Get how_to_run
     *
     * @return text 
     */
    public function getHowToRun()
    {
        return $this->how_to_run;
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
     * Set crack
     *
     * @param string $crack
     */
    public function setCrack($crack)
    {
        $this->crack = $crack;
    }

    /**
     * Get crack
     *
     * @return string 
     */
    public function getCrack()
    {
        return $this->crack;
    }
}
