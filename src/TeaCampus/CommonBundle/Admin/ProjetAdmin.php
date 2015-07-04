<?php

namespace TeaCampus\CommonBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjetAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('targetCountry')
            ->add('enabled')
            ->add('private')
            ->add('date')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('enabled')
            ->add('private')
            ->add('author')
            ->add('date')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Informations Générales')
                ->with('Information', array(
                        'class' => 'col-md-8'
                    ))
                        ->add('title')
                        ->add('author')
                        ->add('founder')
                        ->add('description')
                        ->add('whyCanWork')
                        ->add('businessModel')
                        ->add('targetCountry')
                        ->add('media', 'sonata_type_model_list', array('required' => false), array(
                            'link_parameters' => array(
                                'context' => 'projet',
                                'hide_context' => true
                            )
                        ))
                        ->add('shareWith', 'sonata_type_model_autocomplete', array(
                            'property' => 'email',
                            'multiple' => 'true'
                        ))
                ->end()
                ->with('Stats', array(
                        'class' => 'col-md-4'
                    ))
                        ->add('status')
                        ->add('selectionOfTea')
                        ->add('projectOfTheWeek')
                        ->add('projectOfTheMonth')
                        ->add('private')
                        ->add('enabled')
                        ->add('private')
                        ->add('beginAt', 'genemu_jquerydate', array(
                            'widget' => 'single_text'
                        ))
                        ->add('createdAt', 'genemu_jquerydate', array(
                            'widget' => 'single_text'
                        ))
                        ->add('date', 'date', array(
                            'widget' => 'single_text'
                        ))
                        ->add('tags', 'sonata_type_model_autocomplete', array(
                            'property' => 'name',
                            'multiple' => 'true'
                        ))
                
                ->end()
            ->end()
        ;
    }

    
}
