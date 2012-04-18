<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionUsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	$invitacion = $options['data'];
    	if (empty($invitacion))
    		throw new Exception ("La invitacion no existe en el form InvitacionUsuarioType");
        
        $evento = $invitacion->getInstanciaEvento()->getEvento();
        
        if ($evento->getPedirNumeroAsistentes())
	        $builder->add('numeroAsistentes',null,array('label' => 'Número de asistentes', 'required'=>false));
        if ($evento->getOfrecerViaje())
	        $builder->add('solicitaViaje',null,array('label' => '¿Solicita viaje?', 'required'=>false));
        if ($evento->getOfrecerHospedaje())
	        $builder->add('solicitaHospedaje',null,array('label' => '¿Solicita Hospedaje?', 'required'=>false));
        if ($evento->getPermitirObservaciones())
	        $builder->add('observaciones',null,array('label' => 'Observaciones', 'required'=>false));
        if ($evento->getPermitirSuplente())
	        $builder->add('suplente',null,array('label' => 'En caso de asistir un suplente en lugar de usted, indique su nombre y DNI por favor', 'required'=>false));
        
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
