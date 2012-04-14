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
			->add('region','entity',array(
		    								'label' => 'Región Educativa',
		    								'class' => 'CpmJovenesBundle:RegionEducativa',
		    								'empty_value' => "Todas",
		    								'attr' => array('class' => 'region-selector'),
		    								'preferred_choices' => array("Todas"),
		    								'query_builder' => function($er) {
		    														return $er->createQueryBuilder('r')
		    														->orderBy('r.nombre', 'ASC');
		    														},
		    								'required'=>false
		    							))
		    ->add('distrito','entity',array(
		    							'label' => 'Distrito',
		    							'class' => 'CpmJovenesBundle:Distrito',
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('d')
		    																  ->orderBy('d.nombre', 'ASC');
		    														},
		    							'attr' => array('class' => 'distrito-selector'),
		    							'empty_value' => "Todos",
		    							'preferred_choices' => array("Todos"),
		    							'required'=>false
		    							)) 
			->add('localidad','entity',array(
		    								'label' => 'Localidad',
		    								'class' => 'CpmJovenesBundle:Localidad',
			    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('l')
		    																  ->orderBy('l.nombre', 'ASC');
		    														},
		    														
		    								'attr' => array('class' => 'localidad-selector'),
		    								'empty_value' => "Todas",
		    								'preferred_choices' => array("Todas"),
		    								'required'=>false		    
		    							))
		  	->add('tipoInstitucion','entity',array(
		            									'label' => 'Tipo de Institución',
		            									'class' => 'CpmJovenesBundle:TipoInstitucion',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('t')
															            ->orderBy('t.nombre', 'ASC');
		    														},
        												'required'=>false
        								))
        	->add('otroTipoInstitucion','checkbox',array(
        												'label' => 'Otro tipo de institucion',
		    											'required'=>false
        												))							
        	->add('regionDesde','integer',array(
		            									'label' => 'Desde la región',
		    											'attr' => array('class' => 'input-narrow left-float'),
        												'required'=>false
        								))
        	->add('regionHasta','integer',array(
		            									'label' => 'Hasta la región',
		    											'attr' => array('class' => 'input-narrow left-float'),
        												'required'=>false
        								))
	        											        								
		    ->add('coordinador',null,array( 'label' => 'Docente coordinador',
		    										'required' => false ))
        	->add('escuela', null, array('label' => 'Nombre o numero de escuela',
        								'required' => false))
			->add('proyectos_seleccionados','collection', array('allow_add'=>true, 'allow_delete'=>true))
			->add('batch_action','hidden', array('attr'=>array('class'=>'batch_action_hidden')))
		    ->add('batch_action_type','hidden', array('attr'=>array('class'=>'batch_action_type_hidden')))
            ->add('archivo', 'choice' ,array('label' => 'Archivo cargado' , 
            											'choices' => array(1=>"Con archivo",2=>"Sin archivo"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
                												    														
		    ->add('orderBy','choice',array('label' => 'Ordenar por',
		    								'choices'=>array('p.id'=>'Id', 'coordinador.apellido' => 'Apellido del Coordinador', 'p.titulo'=>'Titulo')
		 	))						
        ;
    }
    
    public function getName()
    {
        return 'cpm_jovenesbundle_proyectosearchtype';
    }
}
