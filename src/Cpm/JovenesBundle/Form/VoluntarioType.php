<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class VoluntarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('telefono')
            ->add('email')
            ->add('domicilio')
            ->add('observaciones')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_voluntariotype';
    }
}
