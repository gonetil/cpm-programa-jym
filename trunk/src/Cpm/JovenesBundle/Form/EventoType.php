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
            ->add('descripcion',null,array('label' => 'Descripción', 'required'=>false))
            ->add('pedirNumeroAsistentes',null,array('label' => '¿solicitar número de asistentes?', 'required'=>false))
            ->add('permitirSuplente',null,array('label' => '¿permitir indicar suplentes?', 'required'=>false))
            ->add('ofrecerHospedaje',null,array('label' => '¿ofrecer hospedaje?', 'required'=>false))
            ->add('ofrecerViaje',null,array('label' => '¿ofrecer viaje?', 'required'=>false))
            ->add('permitirObservaciones',null,array('label' => '¿permitir indicar observaciones?', 'required'=>false))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_eventotype';
    }
}
