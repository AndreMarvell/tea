<?php

namespace TeaCampus\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Sonata\ClassificationBundle\Form\TagsType;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Context;

class TeaAndMeType extends AbstractType
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
    public function __construct(ObjectManager $om, Context $context)
    {
        $this->om = $om;
        $this->context = $context;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('tags', new TagsType($this->om, $this->context))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TeaCampus\CommonBundle\Entity\TeaAndMe'
        ));
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'teaandme';
    }
}
