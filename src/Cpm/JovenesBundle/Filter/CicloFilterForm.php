<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ FormBuilder;



class CicloFilterForm extends ModelFilterForm {


		
	public function buildForm(FormBuilder $builder, array $options) {
		$ciclo = $this->getJYM()->getCicloActivo();
		
		$builder  
		  ->add('ciclo','entity',array(
		    							'label' => 'Ciclo',
		    							'class' => 'CpmJovenesBundle:Ciclo',
		    							'property_path' => false,
		    							'data' => $ciclo,
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('c')
		    																  ->orderBy('c.titulo', 'ASC');
		    														},
		    							'empty_value' => "Cualquiera",
		    							'required'=>false
		    					)) 
		  		
		    					
        ;
	
	}
}