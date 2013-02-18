<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoType extends AbstractType
{
 public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
              ->add('coordinador','entity',
            					array('label' => 'Docente Coordinador',
            						  'class' => 'CpmJovenesBundle:Usuario',
            						  'query_builder' => function($er) {
													        return $er->createQueryBuilder('u')
													            ->orderBy('u.apellido', 'ASC');
    														}
    								    ))
            ->add('titulo')
            ->add('nroAlumnos','integer',array('label'  => 'Número de alumnos', 'attr'=>array('class'=>'number')))
            ->add('esPrimeraVezDocente', 'choice',	array(
            		'label' => '¿participa por primera vez el docente?', 
            		'required'=>false,
    				'choices' => array(1=>"si",0=>"no"),
    				'expanded' => true,
    				 'attr'=>array('class'=>'radios-en-linea'),
    		))
    		->add('esPrimeraVezEscuela', 'choice',	array(
            		'label' => '¿participa por primera vez la escuela?', 
            		'required'=>false,
    				'choices' => array(1=>"si",0=>"no"),
    				'expanded' => true,
    				 'attr'=>array('class'=>'radios-en-linea'),
    		))
    		->add('esPrimeraVezAlumnos', 'choice',	array(
            		'label' => '¿participan por primera vez los alumnos?', 
            		'required'=>false,
    				'choices' => array(1=>"si",0=>"no"),
    				'expanded' => true,
    				 'attr'=>array('class'=>'radios-en-linea'),
    		))
            ->add('colaboradores','collection',array('allow_add'=>true, 'allow_delete'=>true, 'by_reference'=>false, 'type'=>new ColaboradorType()))
            ->add('eje','entity',
            					array('label' => 'Eje',
            						  'class' => 'CpmJovenesBundle:Eje',
            						  'query_builder' => function($er) { return $er->createQueryBuilder('e')->where('e.anulado = 0');}
    								    ))
            ->add('temaPrincipal','entity',
            					array('label' => 'Tema Principal',
            						  'class' => 'CpmJovenesBundle:Tema',
            						  'query_builder' => function($er) { return $er->createQueryBuilder('t')->where('t.anulado = 0');}
    								    ))
            ->add('produccionFinal','entity',
            					array('label' => 'Producción Final',
            						  'class' => 'CpmJovenesBundle:Produccion',
            						  'query_builder' => function($er) { return $er->createQueryBuilder('p')->where('p.anulado = 0');}
    								    ))
            ->add('deQueSeTrata',null,array('label'=>'¿De qué se trata el proyecto?'))
	        ->add('motivoRealizacion',null,array('label'=>'¿Por qué queremos investigar este tema?'))
	        ->add('impactoBuscado',null,array('label'=>'¿Qué impacto tendrá en la comunidad?'))
        	->add('escuela', new EscuelaType(), array('label' => 'Datos de la escuela'))
			->add('requerimientosDeEdicion',null,array('label'=>'Requerimientos de edición'))        	
			->add('color', 'choice',	array(
            		'label' => 'Color', 
            		'required'=>false,
    				'choices' => array("rojo"=>"rojo" , "naranja" => "naranja", "azul" => "azul" , "verde" => "verde"),
    				'expanded' => false,
    				'empty_value' => "",
    				'preferred_choices' => array("")
    				))
			->add('transporte', 'choice',	array(
            		'label' => 'Transporte', 
            		'required'=>false,
    				'choices' => array("tren"=>"Tren" , "colectivo" => "Colectivo", "combi" => "Combi"),
    				'expanded' => false,
    				'empty_value' => "",
    				'preferred_choices' => array("")
    				))
			->add('observaciones',null,array('label'=>'Observaciones'))
        // ->add('coordinador',new UsuarioType(),array('label' => 'Docente Coordinador'))
        
        ;
    }    
    public function getName()
    {
        return 'cpm_jovenesbundle_proyectotype';
    }
}
