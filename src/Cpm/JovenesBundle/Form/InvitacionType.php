<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('fechaCreacion',null,array('label' => 'Fecha de Creación'))
            ->add('aceptoInvitacion',null,array('label' => 'Invitación aceptada'))
            ->add('numeroAsistentes',null,array('label' => 'Número de asistentes'))
            ->add('solicitaViaje',null,array('label' => '¿Solicita viaje?'))
            ->add('solicitaHospedaje',null,array('label' => '¿Solicita Hospedaje?'))
            ->add('observaciones',null,array('label' => 'Observaciones'))
            ->add('suplente',null,array('label' => 'Suplente'))
            ->add('asistio',null,array('label' => '¿Asistió?'))
            ->add('proyecto',null,array('label' => 'Proyecto'))
            ->add('instanciaEvento',null,array('label' => 'Instancia de evento'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
