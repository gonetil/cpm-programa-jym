<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TandaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add('instanciaEvento')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_tandatype';
    }
}
