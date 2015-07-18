<?php

namespace TeaCampus\CommonBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class TeaAndMeAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('locale')
            ->add('title')
            ->add('date')
            ->add('enabled')
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
            ->add('locale')
            ->add('date')
            ->add('enabled')
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
            ->add('enabled')
            ->add('author')
            ->add('video', 'sonata_type_model_list', array('required' => false), array(
                'link_parameters' => array(
                    'context' => 'teaandme',
                    'hide_context' => true
                )
            ))
            ->add('media', 'sonata_type_model_list', array('required' => false), array(
                'link_parameters' => array(
                    'context' => 'file',
                    'hide_context' => true
                )
            ))
            ->add('locale')
            ->add('date', 'date', array(
                'widget' => 'single_text'
            ))
            ->add('tags', 'sonata_type_model_autocomplete', array(
                'property' => 'name',
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
            ->add('locale')
            ->add('title')
            ->add('media')
            ->add('date')
            ->add('enabled')
        ;
    }
}
