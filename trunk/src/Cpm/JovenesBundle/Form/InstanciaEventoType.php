<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaEventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('subtitulo')
            ->add('descripcion')
            ->add('url')
            ->add('lugar')
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add('evento')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_instanciaeventotype';
    }
}
