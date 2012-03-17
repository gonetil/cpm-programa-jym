<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('pedirNumeroAsistentes')
            ->add('permitirSuplente')
            ->add('ofrecerHospedaje')
            ->add('ofrecerViaje')
            ->add('permitirObservaciones')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_eventotype';
    }
}
