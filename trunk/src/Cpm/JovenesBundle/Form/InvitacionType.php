<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            //->add('fechaCreacion',null,array('label' => 'Fecha de Creación'))
            ->add('aceptoInvitacion',null,array('label' => 'Invitación aceptada', 'required'=>false))
            ->add('numeroAsistentes',null,array('label' => 'Número de asistentes', 'required'=>false))
            ->add('solicitaViaje',null,array('label' => '¿Solicita viaje?', 'required'=>false))
            ->add('solicitaHospedaje',null,array('label' => '¿Solicita Hospedaje?', 'required'=>false))
            ->add('observaciones',null,array('label' => 'Observaciones', 'required'=>false))
            ->add('suplente',null,array('label' => 'Suplente', 'required'=>false))
            ->add('asistio',null,array('label' => '¿Asistió?', 'required'=>false))
            ->add('proyecto',null,array('label' => 'Proyecto'))
            ->add('instanciaEvento',null,array('label' => 'Instancia de evento'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
