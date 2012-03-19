<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo',null,array('label' => 'Título'))
            ->add('descripcion',null,array('label' => 'Descripción'))
            ->add('pedirNumeroAsistentes',null,array('label' => '¿solicitar número de asistentes?'))
            ->add('permitirSuplente',null,array('label' => '¿permitir indicar suplentes?'))
            ->add('ofrecerHospedaje',null,array('label' => '¿ofrecer hospedaje?'))
            ->add('ofrecerViaje',null,array('label' => '¿ofrecer viaje?'))
            ->add('permitirObservaciones',null,array('label' => '¿permitir indicar observaciones?'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_eventotype';
    }
}
