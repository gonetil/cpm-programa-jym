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
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_eventotype';
    }
}
