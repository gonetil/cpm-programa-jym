<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('esPrimeraVezDocente', 'choice' ,array('label' => '¿primera vez del docente?',
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
            										))
            ->add('esPrimeraVezEscuela', 'choice' ,array('label' => '¿primera vez de la escuela?', 
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
        											))
            ->add('esPrimeraVezAlumnos', 'choice' ,array('label' => '¿primera vez de los alumnos?' , 
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
            ->add('temaPrincipal','entity',array(
				            							'label' => 'Tema Principal',
				            						 	'class' => 'CpmJovenesBundle:Tema',
				            						 	'empty_value' => "Todos",
		        										'preferred_choices' => array("Todos"),
				            						 	'query_builder' => function($er) {
																	        return $er->createQueryBuilder('t')
															            ->orderBy('t.nombre', 'ASC');
		    														},
        												'required'=>false
    								    ))
            ->add('produccionFinal','entity',array(
		            									'label' => 'Produccion Final',
		            									'class' => 'CpmJovenesBundle:Produccion',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('p')
															            ->orderBy('p.nombre', 'ASC');
		    														},
        												'required'=>false
        								))
		  ->add('coordinador', null,array( 'label' => 'Docente coordinador',
		    										'required' => false ))
            ->add('archivo', 'choice' ,array('label' => 'Archivo cargado' , 
            											'choices' => array(1=>"Con archivo",2=>"Sin archivo"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
            ->add('requerimientosDeEdicion', 'choice' ,array('label' => 'Requiere edición' , 
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
                										
                										;
			$escuela = new EscuelaFilter();	
            $builder->add('escuelaFilter', $escuela->createForm() ,array('label' => 'Escuela'));
            
            $evento = new EventoFilter();
            $builder->add('eventoFilter',$evento->createForm(),array('label'=>'Evento'));
            
            $instanciaEvento = new InstanciaEventoFilter();
  			$builder->add('instanciaEventoFilter',$instanciaEvento->createForm(),array('label'=>'Instancia de Evento'));      	    									
        	    									
            $estado = new EstadoFilter();
            $builder->add('estadoFilter',$estado->createForm(),array('label'=>'Estado'));
        
        	
    }
    
}
