<?php

namespace TeaCampus\CommonBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EventAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('date')
            ->add('rules')
            ->add('adresse')
            ->add('longitude')
            ->add('latitude')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('date')
            ->add('rules')
            ->add('adresse')
            ->add('longitude')
            ->add('latitude')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('rules')
            ->add('adresse')
            ->add('longitude')
            ->add('latitude')
            ->add('image', 'sonata_type_model_list', array('required' => false), array(
                'link_parameters' => array(
                    'context' => 'evenement',
                    'hide_context' => true
                )
            ))
            ->add('participants', 'sonata_type_model_autocomplete', array(
                'property' => 'username',
                'multiple' => 'true'
            ))
            ->add('date')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('date')
            ->add('rules')
            ->add('adresse')
            ->add('longitude')
            ->add('latitude')
        ;
    }
}
