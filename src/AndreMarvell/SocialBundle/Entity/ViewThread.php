<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="am_view_thread")
 * @ORM\Entity
 */
class ViewThread
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;


    /**
     * Denormalized number of views
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $numViews = 0;

    /**
     * Url of the page where the thread lives
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $permalink;
    
    
    function __construct($id) {
        $this->id = $id;
    }

        /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string
     * @return null
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param  string
     * @return null
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    /**
     * Gets the number of views
     *
     * @return integer
     */
    public function getNumViews()
    {
        return $this->numViews;
    }

    /**
     * Sets the number of views
     *
     * @param integer $numViews
     */
    public function setNumViews($numViews)
    {
        $this->numViews = intval($numViews);
    }

    /**
     * Increments the number of views by the supplied
     * value.
     *
     * @param  integer $by Value to increment views by
     * @return integer The new comment total
     */
    public function incrementNumViews($by = 1)
    {
        return $this->numViews += intval($by);
    }
    
    /**
     * Decrements the number of views by the supplied
     * value.
     *
     * @param  integer $by Value to increment views by
     * @return integer The new comment total
     */
    public function decrementNumViews($by = 1)
    {
        return $this->numViews -= intval($by);
    }

    public function __toString()
    {
        return 'View thread #'.$this->getId();
    }
}
