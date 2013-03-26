<?php
/*
 * Created on 26/03/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CorreoUsuarioBatchType extends UsuarioBatchType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	
        $builder
        	->add('preview','hidden',array('attr'=>array('class'=>'preview')))
            ->add('asunto',null,array('required'=>true , 'attr' => array('class' => 'asunto-correo')))
            ->add('cuerpo','textarea',array('required'=>true , 'attr' => array('class' => 'cuerpo-correo')))
           	->add('plantilla','entity',array(
        				            							'label' => 'Plantilla',
        				            						 	'class' => 'CpmJovenesBundle:Plantilla',
        				            						 	'empty_value' => "ninguna",
        		        										'preferred_choices' => array("ninguna"),
        				            						 	'query_builder' => function($er) {
																				        return $er->createQueryBuilder('p')
																				        ->where('p.puedeBorrarse = 1')
																				        ->orderBy('p.codigo', 'ASC');
																				        },
																'required'=>false,
																'attr' => array('class'=>'select-plantilla')
        ))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_correousuariobatchtype';
    }
}