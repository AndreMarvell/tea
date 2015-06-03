<?php

namespace TeaCampus\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
}
