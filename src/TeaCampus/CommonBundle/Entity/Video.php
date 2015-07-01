<?php

namespace TeaCampus\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Video
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Video
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
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=false)
     */
    private $language;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $video;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\ClassificationBundle\Entity\Tag", cascade={"persist"})
     */
    private $tags;
    
    /**
    * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $author;
    
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
        $this->view = new \AndreMarvell\SocialBundle\Entity\ViewThread("video".$this->id);
        $this->like = new \AndreMarvell\SocialBundle\Entity\LikeThread("video".$this->id);
    }
    
    function __construct() {
        $this->date = new \DateTime();
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
     * @return Video
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
     * @return Video
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Video
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
     * Set video
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $video
     *
     * @return Video
     */
    public function setVideo(\Application\Sonata\MediaBundle\Entity\Media $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Video
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
    
    public function __toString() {
        return $this->getTitle();
    }

    /**
     * Set like
     *
     * @param \AndreMarvell\SocialBundle\Entity\LikeThread $like
     *
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Video
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
     * @return Video
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
}
