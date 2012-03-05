<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('esPrimeraVezDocente', 'choice' ,array('label' => '¿primera vez del docente?', 
            											'choices' => array(1=>"si",0=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
            										))
            ->add('esPrimeraVezEscuela', 'choice' ,array('label' => '¿primera vez de la escuela?', 
            											'choices' => array(1=>"si",0=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
        											))
            ->add('esPrimeraVezAlumnos', 'choice' ,array('label' => '¿primera vez de los alumnos?' , 
            											'choices' => array(1=>"si",0=>"no"),
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
//        	->add('escuela', new EscuelaType(), array('label' => 'Datos de la escuela'))
        ;
    }
    
    public function getName()
    {
        return 'cpm_jovenesbundle_proyectosearchtype';
    }
}
