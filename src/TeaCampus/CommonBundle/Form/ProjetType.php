<?php

namespace TeaCampus\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Sonata\ClassificationBundle\Form\TagsType;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Context;

class ProjetType extends AbstractType
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
            ->add('title', null, array(
                    'required'=>true,
                    'translation_domain' => 'TeaCampusCommonBundle',
                    'label'  => 'project.form.title',
                    'attr'=> array('class'=>'form-group')
                ))
            ->add('description')
            ->add('whyCanWork')
            ->add('businessModel')
            ->add('targetCountry',"genemu_jqueryselect2_country")
            ->add('status')
            ->add('founder')
            ->add('beginAt', 'genemu_jquerydate', array(
                'widget' => 'single_text'
            ))
            ->add('createdAt', 'genemu_jquerydate', array(
                'widget' => 'single_text'
            ))
            ->add('private')
            ->add('language', 'language')
            ->add('media', 'sonata_media_type', array(
                     'provider' => 'sonata.media.provider.image',
                     'context'  => 'projet',
                     'data_class'   =>  'Application\Sonata\MediaBundle\Entity\Media',
                     'required'   =>  false,
                     'label'    =>  false
                ))
            ->add('tags', new TagsType($this->om, $this->context))
        ;
        $builder->get('media')->add('contentType', 'hidden');
        $builder->get('media')->add('unlink', 'hidden', ['mapped' => false, 'data' => false]);
        $builder->get('media')->add('binaryContent', 'file', ['label' => false]);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TeaCampus\CommonBundle\Entity\Projet'
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
        return 'tea_projet';
    }
}
