<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	
    $choices = array();
    for($i=2002;$i<2013;$i++) { 
    	$choices[$i]=$i; 
    }
    	
     $builder
            ->add('apellido',null,array('label'=>'Apellido', 'required'=>false))
            ->add('email',null,array('label'=>'Email', 'required'=>false))
        	->add('habilitados','choice',array('label'=>'Habilitados',
        		  								'choices' => array(1=>"Si",2=>"No",0 => "todos"),
                    							'preferred_choices' => array("Todos"),
                								'empty_value' => "Todos",
                								'expanded'=>true,
                								'required'=>false,
                								 'attr'=>array('class'=>'radios-en-linea'),
				))
        	->add('soloCoordinadores','checkbox',array('label'=>'Sólo coordinadores', 'required'=>false))

        	->add('ciclo','entity',array('label' => 'Vinculados a proyectos en el ciclo',
		    							'class' => 'CpmJovenesBundle:Ciclo',
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('c')
		    																  ->orderBy('c.activo', 'DESC');
		    														},
		    							'required'=>false,
		    							'empty_value' => 'No aplica',
		    							'preferred_choices' => array('No aplica')
		    					))
		    ->add('aniosParticipo','choice',array('label'=>'Años en los que indicó que participó', 
		    								  'required' => false,
		    								  'choices' => $choices,
		    								  'expanded' => true,
		    								  'multiple' => true,
		    								  'required' => false,
		    								  'attr'=>array('class'=>'radios-en-linea'),
		    								  ))					
		    					
		 
        ;
    }
    
}
