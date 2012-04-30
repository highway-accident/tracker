<?php
namespace Rooty\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="255", nullable="true")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length="255", nullable="true")
     */
    protected $surname;
    
    /**
     * @ORM\Column(type="string", length="1", nullable="true")
     */
    protected $gender;
    
    /**
     * @ORM\Column(type="date", nullable="true")
     */
    protected $birthday;
    
    /**
     * @ORM\Column(type="string", length="32")
     */
    protected $passkey;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $uploaded = 0;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $downloaded = 0;
    
    /**
     * @var string $avatar_url
     *
     * @ORM\Column(name="avatar_url", type="string", length=255, nullable="true")
     */
    private $avatar_url;
    
    private $avatar_file;
    
    /**
     * @ORM\Column(type="string", length="100", nullable="true")
     */
    protected $icq;
    
    /**
     * @ORM\Column(type="string", length="100", nullable="true")
     */
    protected $skype;
    
    /**
     * @ORM\Column(type="string", length="100", nullable="true")
     */
    protected $jabber;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_added;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $last_activity;
    
    /**
     * @ORM\Column(type="boolean") 
     */
    protected $is_gold = false;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    public function getFullName()
    {
        return sprintf('%s %s', $this->getName(), $this->getSurname());
    }
    
    public function setEmail($email)
    {
        parent::setEmail($email);
    }
    
    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set birthday
     *
     * @param date $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return date 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * Set avatar_url
     *
     * @param string $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatar_url = $avatarUrl;
    }

    /**
     * Get avatar_url
     *
     * @return string 
     */
    public function getAvatarUrl()
    {
        return $this->avatar_url;
    }

    public function getAvatarAbsolutePath()
    {
        return null === $this->avatar_url ? null : $this->getAvatarUploadRootDir().'/'.$this->avatar_url;
    }

    public function getAvatarWebPath()
    {
        return null === $this->avatar_url ? null : $this->getAvatarUploadDir().'/'.$this->avatar_url;
    }

    protected function getAvatarUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getAvatarUploadDir();
    }

    protected function getAvatarUploadDir()
    {
        return 'uploads/avatars';
    }
    
    /**
     * Set icq
     *
     * @param string $icq
     */
    public function setIcq($icq)
    {
        $this->icq = $icq;
    }

    /**
     * Get icq
     *
     * @return string 
     */
    public function getIcq()
    {
        return $this->icq;
    }
    
    /**
     * Set skype
     *
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }
    
    /**
     * Set jabber
     *
     * @param string $jabber
     */
    public function setJabber($jabber)
    {
        $this->jabber = $jabber;
    }

    /**
     * Get jabber
     *
     * @return string 
     */
    public function getJabber()
    {
        return $this->jabber;
    }

    /**
     * Set passkey
     *
     * @param string $passkey
     */
    public function setPasskey($passkey)
    {
        $this->passkey = $passkey;
    }

    /**
     * Get passkey
     *
     * @return string 
     */
    public function getPasskey()
    {
        return $this->passkey;
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
     * Set last_activity
     *
     * @param datetime $lastActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->last_activity = $lastActivity;
    }

    /**
     * Get last_activity
     *
     * @return datetime 
     */
    public function getLastActivity()
    {
        return $this->last_activity;
    }
    
    /**
    /* @ORM\PrePersist()
    /* @ORM\PreUpdate()
    */
    public function preUpload()
    {
        if (null !== $this->avatar_file) {
            $this->avatar_url = uniqid().'.'.$this->avatar_file->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->avatar_file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->avatar_file->move($this->getAvatarUploadRootDir(), $this->avatar_url);
        
        unset($this->avatar_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($avatar_file = $this->getAvatarAbsolutePath()) {
            unlink($avatar_file);
        }
    }
    
    public function setAvatarFile($avatarFile)
    {
        $this->avatar_file = $avatarFile;
    }
    
    public function getAvatarFile()
    {
        return $this->avatar_file;
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
     * Set is_gold
     *
     * @param boolean $isGold
     */
    public function setIsGold($isGold)
    {
        $this->is_gold = $isGold;
    }

    /**
     * Get is_gold
     *
     * @return boolean 
     */
    public function getIsGold()
    {
        return $this->is_gold;
    }
}