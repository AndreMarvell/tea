<?php

namespace TeaCampus\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TeaCampus\CommonBundle\Entity\Repository\ProjetRepository")
 */
class Projet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="why_can_work", type="text", nullable=true)
     */
    private $whyCanWork;
    
    /**
     * @var string
     *
     * @ORM\Column(name="business_model", type="text", nullable=true)
     */
    private $businessModel;
    
    /**
     * @var string
     *
     * @ORM\Column(name="target_country", type="string", length=255, nullable=true)
     */
    private $targetCountry;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="founder", type="string", length=255, nullable=true)
     */
    private $founder;
    
    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, nullable=false)
     */
    private $locale = 'fr';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="project_of_the_week", type="boolean")
     */
    private $projectOfTheWeek = false;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="project_of_the_month", type="boolean")
     */
    private $projectOfTheMonth = false;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="selection", type="boolean")
     */
    private $selectionOfTea = false;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin", type="datetime")
     */
    private $beginAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private = false;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $media;
    
    /**
    * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $author;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media", mappedBy="projet", cascade={"persist"})
     */
    protected $files;
    
    /**
    * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $shareWith;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\ClassificationBundle\Entity\Tag", cascade={"persist"})
     */
    private $tags;
    
    /** 
     *
     * @ORM\OneToOne(targetEntity="AndreMarvell\SocialBundle\Entity\LikeThread", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $like;
    
    /** 
     *
     * @ORM\OneToOne(targetEntity="AndreMarvell\SocialBundle\Entity\ViewThread", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $view;
    
    
    
    /**
     * Creer les thread
     *
     * @return void 
     */
    public function createThread(){
        $this->view = new \AndreMarvell\SocialBundle\Entity\ViewThread("projet".$this->id);
        $this->like = new \AndreMarvell\SocialBundle\Entity\LikeThread("projet".$this->id);
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
     *
     * @return Projet
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set description
     *
     * @param string $description
     *
     * @return Projet
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return Projet
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean
     */
    public function getPrivate()
    {
        return $this->private;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shareWith = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
        
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     *
     * @return Projet
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Projet
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add file
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $file
     *
     * @return Projet
     */
    public function addFile(\Application\Sonata\MediaBundle\Entity\Media $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $file
     */
    public function removeFile(\Application\Sonata\MediaBundle\Entity\Media $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add shareWith
     *
     * @param \Application\Sonata\UserBundle\Entity\User $shareWith
     *
     * @return Projet
     */
    public function addShareWith(\Application\Sonata\UserBundle\Entity\User $shareWith)
    {
        $this->shareWith[] = $shareWith;

        return $this;
    }

    /**
     * Remove shareWith
     *
     * @param \Application\Sonata\UserBundle\Entity\User $shareWith
     */
    public function removeShareWith(\Application\Sonata\UserBundle\Entity\User $shareWith)
    {
        $this->shareWith->removeElement($shareWith);
    }

    /**
     * Get shareWith
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShareWith()
    {
        return $this->shareWith;
    }
    
    public function __toString() {
        return $this->getTitle();
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Projet
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set like
     *
     * @param \AndreMarvell\SocialBundle\Entity\LikeThread $like
     *
     * @return Projet
     */
    public function setLike(\AndreMarvell\SocialBundle\Entity\LikeThread $like = null)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return \AndreMarvell\SocialBundle\Entity\LikeThread
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set view
     *
     * @param \AndreMarvell\SocialBundle\Entity\ViewThread $view
     *
     * @return Projet
     */
    public function setView(\AndreMarvell\SocialBundle\Entity\ViewThread $view = null)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return \AndreMarvell\SocialBundle\Entity\ViewThread
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Add tag
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $tag
     *
     * @return Projet
     */
    public function addTag(\Application\Sonata\ClassificationBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $tag
     */
    public function removeTag(\Application\Sonata\ClassificationBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set whyCanWork
     *
     * @param string $whyCanWork
     *
     * @return Projet
     */
    public function setWhyCanWork($whyCanWork)
    {
        $this->whyCanWork = $whyCanWork;

        return $this;
    }

    /**
     * Get whyCanWork
     *
     * @return string
     */
    public function getWhyCanWork()
    {
        return $this->whyCanWork;
    }

    /**
     * Set businessModel
     *
     * @param string $businessModel
     *
     * @return Projet
     */
    public function setBusinessModel($businessModel)
    {
        $this->businessModel = $businessModel;

        return $this;
    }

    /**
     * Get businessModel
     *
     * @return string
     */
    public function getBusinessModel()
    {
        return $this->businessModel;
    }

    /**
     * Set targetCountry
     *
     * @param string $targetCountry
     *
     * @return Projet
     */
    public function setTargetCountry($targetCountry)
    {
        $this->targetCountry = $targetCountry;

        return $this;
    }

    /**
     * Get targetCountry
     *
     * @return string
     */
    public function getTargetCountry()
    {
        return $this->targetCountry;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Projet
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set founder
     *
     * @param string $founder
     *
     * @return Projet
     */
    public function setFounder($founder)
    {
        $this->founder = $founder;

        return $this;
    }

    /**
     * Get founder
     *
     * @return string
     */
    public function getFounder()
    {
        return $this->founder;
    }

    /**
     * Set beginAt
     *
     * @param \DateTime $beginAt
     *
     * @return Projet
     */
    public function setBeginAt($beginAt)
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    /**
     * Get beginAt
     *
     * @return \DateTime
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Projet
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Projet
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Projet
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set projectOfTheWeek
     *
     * @param boolean $projectOfTheWeek
     *
     * @return Projet
     */
    public function setProjectOfTheWeek($projectOfTheWeek)
    {
        $this->projectOfTheWeek = $projectOfTheWeek;

        return $this;
    }

    /**
     * Get projectOfTheWeek
     *
     * @return boolean
     */
    public function getProjectOfTheWeek()
    {
        return $this->projectOfTheWeek;
    }

    /**
     * Set projectOfTheMonth
     *
     * @param boolean $projectOfTheMonth
     *
     * @return Projet
     */
    public function setProjectOfTheMonth($projectOfTheMonth)
    {
        $this->projectOfTheMonth = $projectOfTheMonth;

        return $this;
    }

    /**
     * Get projectOfTheMonth
     *
     * @return boolean
     */
    public function getProjectOfTheMonth()
    {
        return $this->projectOfTheMonth;
    }

    /**
     * Set selectionOfTea
     *
     * @param boolean $selectionOfTea
     *
     * @return Projet
     */
    public function setSelectionOfTea($selectionOfTea)
    {
        $this->selectionOfTea = $selectionOfTea;

        return $this;
    }

    /**
     * Get selectionOfTea
     *
     * @return boolean
     */
    public function getSelectionOfTea()
    {
        return $this->selectionOfTea;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return Projet
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
