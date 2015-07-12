<?php

namespace Application\Sonata\ClassificationBundle\Admin;

use Sonata\ClassificationBundle\Admin\CategoryAdmin as SonataCategoryAdmin;
use Sonata\AdminBundle\Form\FormMapper;


class CategoryAdmin extends SonataCategoryAdmin
{
    
    /**
    * {@inheritdoc}
    */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('locale', 'language',array(
                    'label'  => 'Locale',
                ))
            ->add('icon', null, array(
                    'label'  => 'Icon',
                ));
    }
    
}

