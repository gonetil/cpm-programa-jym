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
            ->add('esPrimeraVezDocente',
            		'choice',
            		 array(	'label' => '¿participa por primera vez el docente?', 
            		 		'required'=>false,
    						'choices' => array(1=>"si"),
    						'preferred_choices' => array("No"),
    						'empty_value' => "No",
    						'expanded'=>false,
    						'required'=>false,
    						'attr'=>array('class'=>'input-narrow')
            		 		)
            	)->add('esPrimeraVezEscuela',
            		'choice',
            		 array(	'label' => '¿participa por primera vez la escuela?', 
            		 		'required'=>false,
    						'choices' => array(1=>"si"),
    						'preferred_choices' => array("No"),
    						'empty_value' => "No",
    						'expanded'=>false,
    						'required'=>false,
    						'attr'=>array('class'=>'input-narrow')
            		 		)
            	)->add('esPrimeraVezAlumnos',
            		'choice',
            		 array(	'label' => '¿participa por primera vez los alumnos?', 
            		 		'required'=>false,
    						'choices' => array(1=>"si"),
    						'preferred_choices' => array("No"),
    						'empty_value' => "No",
    						'expanded'=>false,
    						'required'=>false,
    						'attr'=>array('class'=>'input-narrow')
            		 		)
            	)
            ->add('colaboradores','collection',array('allow_add'=>true, 'by_reference'=>false, 'type'=>new ColaboradorType()))
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
        // ->add('coordinador',new UsuarioType(),array('label' => 'Docente Coordinador'))
        
        ;
    }    
    public function getName()
    {
        return 'cpm_jovenesbundle_proyectotype';
    }
}
