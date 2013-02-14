<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventoFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	$builder
          ->add('evento','entity',array(
		    											'label' => 'Invitado al evento',
		            									'class' => 'CpmJovenesBundle:Evento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('e')
															            ->orderBy('e.titulo', 'ASC');
		    														},
        												'required'=>false		    				
		    	))
		  ->add('sinInvitacionFlag','checkbox',array(
													'label' => 'Sin invitación al evento',
													'required' => false		  
		  ))  	
		  ;
		  

    }
}