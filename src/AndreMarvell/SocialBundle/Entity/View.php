<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="am_views")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class View
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this view
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="AndreMarvell\SocialBundle\Entity\ViewThread", cascade={"persist"})
     */
    protected $thread;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @var User
     */
    protected $viewer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip_adress", type="string", length=255)
     */
    private $ip;
    
    
    /**
     * Constructor
     */
    function __construct(ViewThread $thread, $ip,  \Application\Sonata\UserBundle\Entity\User $viewer = null) {
        $this->thread = $thread;
        $this->ip     = $ip;
        $this->viewer = $viewer;
    }
    
    
    /**
     * @ORM\prePersist
     */
    public function increase()
    {
        $this->thread->incrementNumViews();
    }

    /**
     * @ORM\preRemove
     */
    public function decrease()
    {
      $this->thread->decrementNumViews();
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
     * @param \AndreMarvell\SocialBundle\Entity\ViewThread $thread
     * @return View
     */
    public function setThread(\AndreMarvell\SocialBundle\Entity\ViewThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \AndreMarvell\SocialBundle\Entity\ViewThread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set viewer
     *
     * @param \Application\Sonata\UserBundle\Entity\User $viewer
     * @return View
     */
    public function setViewer(\Application\Sonata\UserBundle\Entity\User $viewer = null)
    {
        $this->viewer = $viewer;

        return $this;
    }

    /**
     * Get viewer
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getViewer()
    {
        return $this->viewer;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return View
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}
