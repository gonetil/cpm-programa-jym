<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaEventoFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	$builder
          ->add('instanciaEvento','entity',array(
		    											'label' => 'Invitado a la instancia',
		            									'class' => 'CpmJovenesBundle:InstanciaEvento',
		    								    		'empty_value' => "Todas",
		    								            'preferred_choices' => array("Todas"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('ie')
															            ->orderBy('ie.fechaInicio', 'ASC');
		    														},
        												'required'=>false		    				
		    	))
            ->add('confirmoInvitacionAInstancia','choice',
                array(
                    'label'=>'Confirmo invitacion a la instancia',
                    'required'=>false,
                    'choices' => array(3=>"Todos",1=>"Si",2=>"No"),
                    'data' => '3',
                    'empty_value' => false,
                    'expanded'=>false
                ))
            ->add('asistioAInstancia','choice',
                array(
                    'label'=>'Asistio a la instancia',
                    'required'=>false,
                    'choices' => array(3=>"Todos",1=>"Si",2=>"No"),
                    'data' => '3',
                    'empty_value' => false,
                    'expanded'=>false
                ))
		  ;
		
				         
       	$miEvento = new EventoFilter();
       	$builder->add('eventoFilter',$miEvento->createForm($this->getJYM()),array('label'=>'Evento'));
		  
				         
       	$miCiclo = new CicloFilter();
       	$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
		  
		  
    }
}