<?php

namespace TeaCampus\CommonBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ConcoursAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('start')
            ->add('end')
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
            ->add('start')
            ->add('end')
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
            ->add('start')
            ->add('end')
            ->add('organiser')
            ->add('image', 'sonata_type_model_list', array('required' => false), array(
                'link_parameters' => array(
                    'context' => 'concours',
                    'hide_context' => true
                )
            ))
            ->add('partners', 'sonata_type_model_autocomplete', array(
                'property' => 'name',
                'multiple' => 'true'
            ))
            ->add('participants', 'sonata_type_model_autocomplete', array(
                'property' => 'username',
                'multiple' => 'true'
            ))
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
            ->add('start')
            ->add('end')
        ;
    }
}
