<?php

namespace Application\Sonata\ClassificationBundle\Form;


use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Application\Sonata\ClassificationBundle\Entity\Context;
 
class TagsType extends AbstractType
{
    
    /**
     * @var ObjectManager
     */
    private $om;
    
    /**
     * @var ObjectManager
     */
    private $context;
    
    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, Context  $context)
    {
        $this->om = $om;
        $this->context = $context;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new DataTransformer\TagsDataTransformer($this->om, $this->context));
    }
 
    public function getParent()
    {
        return 'text';
    }
 
    public function getName()
    {
        return 'tags';
    }
}