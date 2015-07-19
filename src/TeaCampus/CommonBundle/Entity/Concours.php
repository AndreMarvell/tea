<?php

namespace TeaCampus\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concours
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Concours
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
     * @var \TeaCampus\CommonBundle\Entity\Partner
     * @ORM\ManyToOne(targetEntity="TeaCampus\CommonBundle\Entity\Partner", cascade={"persist"}, fetch="LAZY")
     */
    private $organiser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $image;
    
    /**
    * @ORM\ManyToMany(targetEntity="TeaCampus\CommonBundle\Entity\Partner")
    * @ORM\JoinColumn(nullable=true)
    */
    private $partners;
        
    /**
    * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $participants;


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
     * @return Concours
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
     * Set organiser
     *
     * @param string $organiser
     *
     * @return Concours
     */
    public function setOrganiser($organiser)
    {
        $this->organiser = $organiser;

        return $this;
    }

    /**
     * Get organiser
     *
     * @return string
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Concours
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Concours
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->partners = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     *
     * @return Concours
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add partner
     *
     * @param \TeaCampus\CommonBundle\Entity\Partner $partner
     *
     * @return Concours
     */
    public function addPartner(\TeaCampus\CommonBundle\Entity\Partner $partner)
    {
        $this->partners[] = $partner;

        return $this;
    }

    /**
     * Remove partner
     *
     * @param \TeaCampus\CommonBundle\Entity\Partner $partner
     */
    public function removePartner(\TeaCampus\CommonBundle\Entity\Partner $partner)
    {
        $this->partners->removeElement($partner);
    }

    /**
     * Get partners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartners()
    {
        return $this->partners;
    }

    /**
     * Add participant
     *
     * @param \Application\Sonata\UserBundle\Entity\User $participant
     *
     * @return Concours
     */
    public function addParticipant(\Application\Sonata\UserBundle\Entity\User $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \Application\Sonata\UserBundle\Entity\User $participant
     */
    public function removeParticipant(\Application\Sonata\UserBundle\Entity\User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
    
    public function __toString() {
        return $this->getTitle();
    }

    
}
