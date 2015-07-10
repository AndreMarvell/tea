<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="am_rates")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Rate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this rate
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="AndreMarvell\SocialBundle\Entity\RateThread", cascade={"persist"})
     */
    protected $thread;
    
    
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @var Users
     */
    protected $rater;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;
    
    
    /**
     * Constructor
     */
    function __construct() {
        
    }
    
    
    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->thread->incrementNumRates();
        $this->thread->calculateAverage($this->rate);
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
      $this->thread->decrementNumRates();
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
     * @param \AndreMarvell\SocialBundle\Entity\RateThread $thread
     * @return Rate
     */
    public function setThread(\AndreMarvell\SocialBundle\Entity\RateThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \AndreMarvell\SocialBundle\Entity\RateThread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set rater
     *
     * @param \Application\Sonata\UserBundle\Entity\User $rater
     * @return Rater
     */
    public function setRater(\Application\Sonata\UserBundle\Entity\User $rater = null)
    {
        $this->rater = $rater;

        return $this;
    }

    /**
     * Get rater
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getRater()
    {
        return $this->rater;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }
}
