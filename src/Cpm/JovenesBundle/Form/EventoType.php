<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
         //   ->add('ciclo')
            ->add('titulo',null,array('label' => 'Título'))
            ->add('descripcion',null,array('label' => 'Descripción', 'required'=>false))
            ->add('pedirNumeroAsistentes','choice',array('label' => '¿Solicitar número de asistentes?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('permitirSuplente','choice',array('label' => '¿permitir indicar suplentes?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('ofrecerHospedaje','choice',array('label' => '¿Ofrecer hospedaje?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('ofrecerViaje','choice',array('label' => '¿Ofrecer viaje?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('permitirObservaciones','choice',array('label' => '¿Permitir Observaciones?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('preguntarSolicitaTren','choice',array('label' => '¿Preguntar si solicita tren?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('solicitarListaInvitados','choice',array('label' => '¿Solicitar que se cargue la lista de invitados?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('numeroMaximoInvitados',null,array('label' => 'Limitar la lista de invitados a:', 'attr'=>array('class'=>'inline number'), 'required' => false,'data' => '0'))
            ->add('solicitarDuracionPresentacion','choice',array('label' => '¿Solicitar la duración (estimada) de la presentación?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('permitirModificarLaInvitacion','choice',array('label' => '¿Permitir modificar la invitación una vez aceptada o confirmada?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('solicitarArchivoAdjunto','choice',array('label' => '¿Solicitar que se adjunte un archivo?', 'expanded'=>true, 'choices' => array(1=>"si",0=>"no"), 'attr'=>array('class'=>'inline')))
            ->add('descripcionArchivoAdjunto',null,array('label'=>'Descripción del campo de archivo adjunto'))
            ->add('action','choice',array('label' => 'Acción a ejecutar una vez confirmada la invitación',
            	  'choices' => array('ConfirmacionCamposChapa'=>"Confirmación de datos del proyecto para Chapadmalal"),
				  'expanded'=>false, 'empty_value' => "Ninguna",'attr'=>array('class'=>'inline'), 'required' => false))

        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_eventotype';
    }
}
