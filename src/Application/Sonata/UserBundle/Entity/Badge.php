<?php

namespace Application\Sonata\UserBundle\Entity;


/**
 * Badge
 */
class Badge
{
    /**
     * 
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;
    
    /**
     * @var string
     */
    private $icone;

    /**
     * @var string
     */
    private $description;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    protected $image;

    
    


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Badge
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Badge
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
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     *
     * @return Badge
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
    
    public function __toString() {
        return $this->nom;
    }


    /**
     * Set icone
     *
     * @param string $icone
     *
     * @return Badge
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get icone
     *
     * @return string
     */
    public function getIcone()
    {
        return $this->icone;
    }
}
