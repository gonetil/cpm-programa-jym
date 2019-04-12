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
																	        			->innerJoin('e.ciclo','c')//->where('c.activo = 1')
															            				->orderBy('c.anio', 'DESC')
															            				->orderBy('e.titulo', 'ASC')
															            				;
		    														},
        												'required'=>false		    				
		    	))
		  ->add('sinInvitacionFlag','checkbox',array(
													'label' => 'Sin invitación al evento',
													'required' => false		  
		  ))
            ->add('confirmoInvitacionFlag','choice',array(
                'label' => 'Invitación confirmada',
                'required' => false,
                'choices' => array(3=>"Todos",1=>"Si",2=>"No"),
                'data' => '3',
                'empty_value' => false,
                'expanded'=>false
            ))
            ->add('asistioAlEventoFlag','choice',array(
                'label' => 'Asistió al evento',
                'required' => false,
                'choices' => array(3=>"Todos",1=>"Si",2=>"No"),
                'data' => '3',
                'empty_value' => false,
                'expanded'=>false
            ))
            ->add('rechazoInvitacionFlag','choice',array(
                'label' => 'Rechazó la invitación al evento',
                'required' => false,
                'choices' => array(3=>"Todos",1=>"Si",2=>"No"),
                'data' => '3',
                'empty_value' => false,
                'expanded'=>false
            ))

		  ;

       		$miCiclo = new CicloFilter();
       		$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
		  

    }
}