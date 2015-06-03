<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RateThread
 *
 * @ORM\Table(name="am_rate_thread")
 * @ORM\Entity
 */
class RateThread
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="numRate", type="integer")
     */
    private $numRate = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="average", type="float")
     */
    private $average = 0;
    
    function __construct($id) {
        $this->id = $id;
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
     * Set numRate
     *
     * @param integer $numRate
     * @return RateThread
     */
    public function setNumRate($numRate)
    {
        $this->numRate = $numRate;

        return $this;
    }

    /**
     * Get numRate
     *
     * @return integer 
     */
    public function getNumRate()
    {
        return $this->numRate;
    }

    /**
     * Set average
     *
     * @param float $average
     * @return RateThread
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float 
     */
    public function getAverage()
    {
        return $this->average;
    }
    
    /**
     * Increments the number of rates by the supplied
     * value.
     *
     * @param  integer $by Value to increment rates by
     * @return integer The new rate total
     */
    public function incrementNumRates($by = 1)
    {
        return $this->numRate += intval($by);
    }
    
    /**
     * Decrements the number of rates by the supplied
     * value.
     *
     * @param  integer $by Value to increment rates by
     * @return integer The new rate total
     */
    public function decrementNumRates($by = 1)
    {
        return $this->numRate -= intval($by);
    }
    
    public function calculateAverage($rate) {
        if($this->numRate==1){
            $this->setAverage($rate);
        }else{
            $new_rate = (($this->average/($this->numRate-1))+$rate)/$this->numRate;
            $this->setAverage($new_rate);
        }
    }

    /**
     * Set id
     *
     * @param string $id
     * @return RateThread
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
