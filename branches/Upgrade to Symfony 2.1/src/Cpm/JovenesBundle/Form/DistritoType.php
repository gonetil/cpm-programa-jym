<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DistritoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('region', null, array('required'=>true))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }
}
