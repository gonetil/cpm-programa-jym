<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class InvitacionUsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	$invitacion = $options['data'];
    	if (empty($invitacion))
    		throw new \Exception ("La invitacion no existe en el form InvitacionUsuarioType");

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
     	if ($evento->getPreguntarSolicitaTren())
		     $builder->add('solicitaTren',null,array('label' => '¿Solicita tren?', 'required'=>false));
		if ($evento->getSolicitarListaInvitados())
			$builder->add('invitados','hidden',array('label' => 'Lista de invitados', 'required'=>false));
		if ($evento->getSolicitarDuracionPresentacion())
			$builder->add('duracion',null,array('label' => 'Duración (en minutos) estimada de la presentación', 'required'=>false, 'attr'=>array('class'=>'inline number'),'data' => '0' ));
    if ($evento->getSolicitarArchivoAdjunto())
      $builder->add('archivoAdjunto','file',array('label' => $evento->getDescripcionArchivoAdjunto(), 'required'=>true, 'attr'=>array('class'=>'inline') ));

		 if ($action = $evento->getAction())
			{
				$type = "Cpm\\JovenesBundle\\Form\\".$action."Type";
				$form = new $type();
				$builder->add('embeddedForm',$form,array('label'=>'Confirme los siguientes datos de su proyecto'));
			}


    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
