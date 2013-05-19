<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ FormBuilder;



class ArchivoFilterForm extends ModelFilterForm {


		
	public function buildForm(FormBuilder $builder, array $options) {
		
		$builder  
			->add('nombre', null,array( 'label' => 'Nombre',
		    										'required' => false ))
        ;
	
	}
	
}