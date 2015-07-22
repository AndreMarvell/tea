<?php

namespace AndreMarvell\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AndreMarvell\NotificationBundle\Entity\Repository\NotificationRepository")
 */
class Notification
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
    * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true) 
    */
    private $sender;
    
    /**
    * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
    */
    private $receiver;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
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
     * @ORM\Column(name="open", type="boolean")
     */
    private $open = false;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="text")
     */
    private $permalink;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;
    
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return Notification
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
     * @return Notification
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
     * @return Notification
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
     * Set open
     *
     * @param boolean $open
     *
     * @return Notification
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return boolean
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     *
     * @return Notification
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
    
    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Notification
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set sender
     *
     * @param \Application\Sonata\UserBundle\Entity\User $sender
     *
     * @return Notification
     */
    public function setSender(\Application\Sonata\UserBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \Application\Sonata\UserBundle\Entity\User $receiver
     *
     * @return Notification
     */
    public function setReceiver(\Application\Sonata\UserBundle\Entity\User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
    
    public function since($locale)
    {

        $time = time() - $this->date->getTimestamp();

        $tokens = array (
            'fr' => array(
                            31536000 => 'année',
                            2592000 => 'mois',
                            604800 => 'semaine',
                            86400 => 'jour',
                            3600 => 'heure',
                            60 => 'minute',
                            1 => 'seconde'
                        ),
            'en' => array(
                            31536000 => 'year',
                            2592000 => 'month',
                            604800 => 'week',
                            86400 => 'day',
                            3600 => 'hour',
                            60 => 'minute',
                            1 => 'second'
                        )
        );
        
        $token_local = (isset($tokens[$locale]))? $tokens[$locale] : array();
        
        foreach ($token_local as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            $humanTiming =  $numberOfUnits.' '.$text.(($numberOfUnits>1 && $text != 'mois')?'s':'');
            break;
        }
        
        if($locale == "fr"){
            return 'Il y a '.$humanTiming;
        }elseif($locale == "en"){
            return $humanTiming.' ago';
        }else{
            return "";
        }

    }
}
