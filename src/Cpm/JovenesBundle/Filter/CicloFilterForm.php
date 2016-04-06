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
		    							'property_path' => 'ciclo',
		    							'data' => $ciclo,
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('c')
		    																  ->orderBy('c.id', 'DESC');
		    																  //->orderBy('c.titulo', 'ASC');
		    														},
		    							'required'=>true
		    					)) 
		  		
		    					
        ;
	
	}
	
	public function getCicloActivo() {
		return $this->getJYM()->getCicloActivo();
	}
}