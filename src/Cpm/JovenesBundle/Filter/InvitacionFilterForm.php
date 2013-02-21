<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
 		$builder->add('coordinador',null,array(
 				'label'=>'Apellido Coordinador',
 				'required' => false ))
 		->add('fechaMin', 'date', array (
			'label' => 'Fecha Desde',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT,
		))->add('fechaMax', 'date', array (
			'label' => 'Fecha Hasta',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT
		))->add('instanciaEvento', 'entity', array (
			'label' => 'Instancia',
			'class' => 'CpmJovenesBundle:InstanciaEvento',
			'query_builder' => function($er) { 
									return $er->createQueryBuilder('ie')
											->innerJoin('ie.evento','e')
											->innerJoin('e.ciclo','c')
											->where('c.activo = 1')
											;
			},
			'empty_value' => "Todas",
			'required' => false
		))->add('proyecto', 'entity', array (
			'label' => 'Proyecto',
			'class' => 'CpmJovenesBundle:Proyecto',
			'query_builder' => function($er) { 
									return $er->createQueryBuilder('p')
											->innerJoin('p.ciclo','c')
											->where('c.activo = 1')
											;
			},
			'empty_value' => "Todos",
			'required' => false
		))
		->add('solicitaHospedaje', 'choice' ,array('label' => 'Solicita Hospedaje?',
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
            										))
        ->add('solicitaViaje', 'choice' ,array('label' => 'Solicita Viaje?',
            											'choices' => array(1=>"si",2=>"no"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
            										))
		->add('suplente', null, array (
			'required' => false
		))
		
        ;
        				         
       	$miCiclo = new CicloFilter();
       	$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>' '));
    }
    
}
