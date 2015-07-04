<?php

namespace Application\Sonata\ClassificationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Context;

class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $entityClass;
    private $entityType;
    private $entityRepository;
    private $context;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, Context $context)
    {
        $this->om = $om;
        $this->setEntityClass("Application\Sonata\ClassificationBundle\Entity\Tag");
        $this->setEntityRepository("ApplicationSonataClassificationBundle:Tag");
        $this->setEntityType("Tag");
        $this->setContext($context);
    }

    /**
     * Transforms an object  to a string .
     *
     * @param  Tags|null $tags
     * @return string
     */
    public function transform($tags)
    {
        if (null === $tags) {
            return "";
        }
        
        $names = array();
        
        foreach ($tags as $tag){
           $names[] = $tag->getName(); 
        }

        return implode(', ', $names);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string text
     *
     * @return Product|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($text)
    {
        if (!$text) {
            return null;
        }
        
        $names = array_filter(array_map('trim', explode(',', $text)));
        $tags = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($names as $name){
            $tag = $this->om->getRepository($this->entityRepository)->findOneBy(array('name'=>ucfirst($name),'context'=>$this->context));
            if(!is_null($tag)){
               $tags[] = $tag; 
            }else{
               $tag = new \Application\Sonata\ClassificationBundle\Entity\Tag();
               $tag->setEnabled(true);
               $tag->setName(ucfirst($name));
               $tag->setContext($this->context);
               
               $this->om->persist($tag);
               $this->om->flush();
               
               $tags[] = $tag; 
            }
        }
        
        return $tags;
    }
    
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }
 
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }
 
    public function setEntityRepository($entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }
    
    function setContext($context) {
        $this->context = $context;
    }


    
    
    
}