<?php

namespace AndreMarvell\SocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="am_like_thread")
 * @ORM\Entity
 */
class LikeThread
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;


    /**
     * Denormalized number of likes
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $numLikes = 0;

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
     * Gets the number of likes
     *
     * @return integer
     */
    public function getNumLikes()
    {
        return $this->numLikes;
    }

    /**
     * Sets the number of likes
     *
     * @param integer $numLikes
     */
    public function setNumLikes($numLikes)
    {
        $this->numLikes = intval($numLikes);
    }

    /**
     * Increments the number of likes by the supplied
     * value.
     *
     * @param  integer $by Value to increment likes by
     * @return integer The new comment total
     */
    public function incrementNumLikes($by = 1)
    {
        return $this->numLikes += intval($by);
    }
    
    /**
     * Decrements the number of likes by the supplied
     * value.
     *
     * @param  integer $by Value to increment likes by
     * @return integer The new comment total
     */
    public function decrementNumLikes($by = 1)
    {
        return $this->numLikes -= intval($by);
    }

    public function __toString()
    {
        return 'Like thread #'.$this->getId();
    }
}
