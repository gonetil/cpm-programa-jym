<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LocalidadType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('distrito', null, array('required'=>true))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }
}
