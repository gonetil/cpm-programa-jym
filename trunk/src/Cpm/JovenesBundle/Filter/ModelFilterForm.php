<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ AbstractType;
use Symfony \ Component \ Form \ FormBuilder;
use Cpm\JovenesBundle\Service\JYM;

abstract class ModelFilterForm extends AbstractType {
	
	protected $modelFilter; 
	protected $jym;
	protected $suffix;
	
	public function __construct(ModelFilter $modelFilter, JYM $jym, $suffix=""){
		$this->modelFilter = $modelFilter;
		$this->jym = $jym;
		$this->suffix = $suffix;
	}
	
	public  function getModelFilter(){
		return $this->modelFilter;
	}
	
	public function getName(){
        return 'cpm_jovenesbundle_modelfilter'.$this->suffix;
	}
	
	public function getJYM(){
        return $this->jym;
	}
	
	
}