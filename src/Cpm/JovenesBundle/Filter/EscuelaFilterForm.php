<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EscuelaFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
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
	        											        								
		    ->add('nombre', null, array('label' => 'Nombre o numero de escuela',
        								'required' => false))
		    					
        ;
          		$miCiclo = new CicloFilter();
       		$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
    }
    
}
