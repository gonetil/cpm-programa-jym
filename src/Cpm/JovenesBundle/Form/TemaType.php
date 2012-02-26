<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TemaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('anulado',null,array('required' => false))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_tematype';
    }
}
