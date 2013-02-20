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
		 $filter = $options['data'];
		 
		parent::buildForm($builder, $options);
		$builder
//			->add('sort', null)
			->add('batch_action','hidden')
		    ->add('batch_action_type','hidden')
		    ->add('modelFilter', $this->modelFilterForm)
		    ->add('sortField','choice',array('label'=>'Ordenar por',
		    								'choices'=> $mf->getSortFields(),
		    								'required'=>true,
		    								'expanded'=>false))
			->add('sortOrder','choice',array('label'=>'Orden',
				    								'choices'=> array("ASC"=>"Ascendente","DESC"=>"Descendente"),
				    								'data' => 'ASC',
				    								'required'=>false,
				    								'expanded'=>true,
				    								'multiple'=>false))
		
        	->add('selectedEntities','entity', 
        			array('class'=>$mf->getTargetEntity(), 
        			'multiple'=>true, 
        			'expanded'=>true, 
        			'required' =>true, 
        			'query_builder' => function($er) use ($mf,$filter){
        						$qb = $er->filterQuery($mf,$filter->getCicloActivo(),$filter->getSortField(),$filter->getSortOrder())
								->setFirstResult(($filter->getPageNumber() - 1) * $filter->getPageSize())
								->setMaxResults($filter->getPageSize())
								;
        						return $qb;} ))
        ;
        
//      $invitacion = $options['data'];
	}

	public function getName() {
		return 'cpm_jovenesbundle_filter';
	}
	

}