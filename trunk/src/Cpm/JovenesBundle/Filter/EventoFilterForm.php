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
																	        return $er->createQueryBuilder('e')->innerJoin('e.ciclo','c')
																	        			->where('c.activo = 1')
															            				->orderBy('e.titulo', 'ASC')
//															            				->orderBy('c.titulo','ASC')
//															            				->orderBy('c.activo','desc')
															            				;
		    														},
        												'required'=>false		    				
		    	))
		  ->add('sinInvitacionFlag','checkbox',array(
													'label' => 'Sin invitación al evento',
													'required' => false		  
		  )) 	
		  ;
		  
		         
       		$miCiclo = new CicloFilter();
       		$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
		  

    }
}