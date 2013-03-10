<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CicloType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('anio')
            //->add('activo')
            //->add('etapaActual')
            //->add('historial')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_ciclotype';
    }
}
