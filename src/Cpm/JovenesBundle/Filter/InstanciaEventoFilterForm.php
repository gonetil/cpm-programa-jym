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
		  ;
    }
}