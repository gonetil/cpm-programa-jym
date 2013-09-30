<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AuditorioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('anulado')
            ->add('producciones',null,array('label'=>'Tipos de producciones soportadas'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_auditoriotype';
    }
}
