<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ FormBuilder;

class CicloFilterForm extends ModelFilterForm {

		
	public function buildForm(FormBuilder $builder, array $options) {
	$builder  
		  ->add('ciclo','entity',array(
		    							'label' => 'Ciclo',
		    							'class' => 'CpmJovenesBundle:Ciclo',
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('c')
		    																  ->orderBy('c.activo', 'DESC')
		    																  ->orderBy('c.titulo', 'ASC');;
		    														},
		    							'empty_value' => "Cualquiera",
		    							'preferred_choices' => array("Cualquiera"),
		    							'required'=>false
		    					)) 
		  		
		    					
        ;
	
	}
}