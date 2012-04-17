<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ AbstractType;
use Symfony \ Component \ Form \ FormBuilder;

abstract class ModelFilterForm extends AbstractType {
	
	protected $modelFilter; 
	protected $suffix;
	
	public function __construct(ModelFilter $modelFilter, $suffix=""){
		$this->modelFilter = $modelFilter;
		$this->suffix = $suffix;
	}
	
	public  function getModelFilter(){
		return $this->modelFilter;
	}
	
	public function getName(){
        return 'cpm_jovenesbundle_modelfilter'.$this->suffix;
	}
}