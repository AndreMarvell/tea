<?php

namespace TeaCampus\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Sonata\ClassificationBundle\Form\TagsType;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Context;
use Doctrine\ORM\EntityRepository;

class VideoType extends AbstractType
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
     * @var Locale
     */
    private $locale;
    
    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, Context $context, $locale = 'fr')
    {
        $this->om = $om;
        $this->context = $context;
        $this->locale = $locale;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('tags', new TagsType($this->om, $this->context))
            ->add('category', 'entity', array(
                'class' => 'Application\Sonata\ClassificationBundle\Entity\Category',
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                              ->join('c.context','co')
                              ->leftJoin('c.children','ch')
                              ->where('co.name  = :context')
                              ->andWhere('c.locale  = :locale')
                              ->andWhere('ch.id IS NULL')
                              ->setParameter('context', 'video')
                              ->setParameter('locale', $this->locale)
                              ->orderBy('c.name', 'ASC');
                    },
                'empty_value' => 'Dans quelle catégorie placez-vous cette vidéo'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TeaCampus\CommonBundle\Entity\Video'
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
        return 'tea_formation';
    }
}
