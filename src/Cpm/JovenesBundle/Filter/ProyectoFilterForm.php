<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
    
            ->add('esPrimeraVezEscuela', 'choice' ,array('label' => '¿primera vez de la institución?', 
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
            ->add('temasPrincipales','entity',array(
                                                        'label' => 'Tema Principal',
                                                        'class' => 'CpmJovenesBundle:Tema',
                                                        'query_builder' => function($er) {
                                                            return $er->createQueryBuilder('t')
                                                                ->orderBy('t.nombre', 'ASC');
                                                        },
                                                        'required'=>false,
                                                        'multiple' => true
                                                    ))
            ->add('produccionesFinales','entity',array(
		            									'label' => 'Produccion Final',
		            									'class' => 'CpmJovenesBundle:Produccion',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('p')
															            ->orderBy('p.nombre', 'ASC');
		    														},
        												'required'=>false,
                                                        'multiple' => true
        								))
		  ->add('coordinador', null,array( 'label' => 'Coordinador',
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
       			->add('color', 'choice',	array( 'label' => 'Color', 
            		'required'=>false,
    				'choices' => array("rojo"=>"rojo" , "naranja" => "naranja", "azul" => "azul" , "verde" => "verde"),
    				'expanded' => false,
            		'preferred_choices' => array("Todos"),
        			'empty_value' => "Todos",
    				))         										
       			->add('transporte', 'choice',	array( 'label' => 'Transporte', 
            		'required'=>false,
    				'choices' => array("tren"=>"Tren" , "colectivo" => "Colectivo", "combi" => "Combi"),
     				'expanded' => false,
            		'preferred_choices' => array("Todos"),
        			'empty_value' => "Todos",
    				))         
 				->add('deQueSeTrata', null,array( 'label' => 'Descripción',
		    										'required' => false ))
                ->add('titulo', null,array( 'label' => 'Titulo del proyecto',
                'required' => false ))
	            ->add('ejes','entity',array('label' => 'Eje',
		            									'class' => 'CpmJovenesBundle:Eje',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('p')
															            ->orderBy('p.nombre', 'ASC');
		    														},
        												'required'=>false,
                                                        'multiple'=>true
        								))
                			;
	
			$escuela = new EscuelaFilter();	
            $builder->add('escuelaFilter', $escuela->createForm($this->getJYM()) ,array('label' => 'Institución'));

            $usuario= new UsuarioFilter();	
            $builder->add('usuarioFilter', $usuario->createForm($this->getJYM()) ,array('label' => 'UsuarioFilter'));
                        
            $evento = new EventoFilter();
            $builder->add('eventoFilter',$evento->createForm($this->getJYM()),array('label'=>'Evento'));
            
            $instanciaEvento = new InstanciaEventoFilter();
  			$builder->add('instanciaEventoFilter',$instanciaEvento->createForm($this->getJYM()),array('label'=>'Instancia de Evento'));      	    									
        	    									
            $estado = new EstadoFilter();
            $builder->add('estadoFilter',$estado->createForm($this->getJYM()),array('label'=>'Estado'));
       
       		$miCiclo = new CicloFilter();
       		$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
        	
    }
    
}
