<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="am_likes")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this like
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="AndreMarvell\SocialBundle\Entity\LikeThread", cascade={"persist"})
     */
    protected $thread;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @var User
     */
    protected $liker;
    
    
    /**
     * Constructor
     */
    function __construct(LikeThread $thread, \Application\Sonata\UserBundle\Entity\User $liker) {
        $this->thread = $thread;
        $this->liker = $liker;
    }
    
    
    /**
     * @ORM\prePersist
     */
    public function increase()
    {
        $this->thread->incrementNumLikes();
    }

    /**
     * @ORM\preRemove
     */
    public function decrease()
    {
      $this->thread->decrementNumLikes();
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
     * Set thread
     *
     * @param \AndreMarvell\SocialBundle\Entity\LikeThread $thread
     * @return Like
     */
    public function setThread(\AndreMarvell\SocialBundle\Entity\LikeThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \AndreMarvell\SocialBundle\Entity\LikeThread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set liker
     *
     * @param \Application\Sonata\UserBundle\Entity\User $liker
     * @return Like
     */
    public function setLiker(\Application\Sonata\UserBundle\Entity\User $liker = null)
    {
        $this->liker = $liker;

        return $this;
    }

    /**
     * Get liker
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getLiker()
    {
        return $this->liker;
    }
}
