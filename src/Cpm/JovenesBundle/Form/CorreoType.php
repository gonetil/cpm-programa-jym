<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CorreoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('asunto')
            ->add('cuerpo')
            ->add('destinatario')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_correotype';
    }
}
