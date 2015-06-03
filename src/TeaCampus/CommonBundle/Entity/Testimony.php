<?php

namespace TeaCampus\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Testimony
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Testimony
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
     * @ORM\Column(name="testimony", type="text")
     */
    private $testimony;
    
    /**
    * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set testimony
     *
     * @param string $testimony
     *
     * @return Testimony
     */
    public function setTestimony($testimony)
    {
        $this->testimony = $testimony;

        return $this;
    }

    /**
     * Get testimony
     *
     * @return string
     */
    public function getTestimony()
    {
        return $this->testimony;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Testimony
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
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Testimony
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
}
