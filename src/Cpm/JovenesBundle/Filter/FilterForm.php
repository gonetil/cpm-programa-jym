<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ AbstractType;
use Symfony \ Component \ Form \ FormBuilder;

class FilterForm extends AbstractType {
	
	private $modelFilterForm;

	public function __construct(ModelFilterForm $modelfilterform){
		$this->modelFilterForm = $modelfilterform;
	}
	
	public function buildForm(FormBuilder $builder, array $options) {
		$mf = $this->modelFilterForm->getModelFilter();
		 
		parent::buildForm($builder, $options);
		$builder
//			->add('sort', null)
			->add('batch_action','hidden')
		    ->add('batch_action_type','hidden')
		    ->add('modelFilter', $this->modelFilterForm)
        	->add('selectedEntities','entity', array('class'=>$mf->getTargetEntity(), 'multiple'=>true, 'expanded'=>true, 'required' =>true, 'query_builder' => function($er) use ($mf){return $er->filterQuery($mf);} ))
        ;
        
//      $invitacion = $options['data'];
	}

	public function getName() {
		return 'cpm_jovenesbundle_filter';
	}
}