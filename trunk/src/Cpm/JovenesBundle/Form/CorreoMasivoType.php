<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CorreoMasivoType extends ProyectoBatchType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	
        $builder
        	->add('preview','hidden',array('attr'=>array('class'=>'preview')))
            ->add('ccColaboradores','checkbox',array('required'=>false,'label'=>'Enviar a los colaboradores ?'))
            ->add('ccEscuelas','checkbox',array('required'=>false,'label'=>'Enviar a las escuelas ?'))
       	    ->add('ccCoordinadores','checkbox',array('required'=>false,'label'=>'Enviar a los coordinadores ?'))
        
            ->add('asunto',null,array('required'=>true , 'attr' => array('class' => 'asunto-correo')))
            ->add('cuerpo','textarea',array('required'=>true , 'attr' => array('class' => 'cuerpo-correo')))
           	->add('plantilla','entity',array(
        				            							'label' => 'Plantilla',
        				            						 	'class' => 'CpmJovenesBundle:Plantilla',
        				            						 	'empty_value' => "ninguna",
        		        										'preferred_choices' => array("ninguna"),
        				            						 	'query_builder' => function($er) {
																				        return $er->createQueryBuilder('p')
																				        ->orderBy('p.codigo', 'ASC');
																				        },
																'required'=>false,
																'attr' => array('class'=>'select-plantilla')
        ))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_correomasivotype';
    }
}