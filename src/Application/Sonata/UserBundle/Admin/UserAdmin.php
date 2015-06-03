<?php

namespace Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;


class UserAdmin extends SonataUserAdmin
{
    
    /**
    * {@inheritdoc}
    */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->tab('Extra')
                ->add('badges', 'sonata_type_model_autocomplete', array(
                    'property' => 'nom',
                    'multiple' => 'true'
                ))
                ->add('avatar', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context' => 'avatar',
                        'hide_context' => true
                    )
                ))
//                ->with('Recompenses')
//                    
//                ->end()
            ->end()
        ;
    }
    
}

