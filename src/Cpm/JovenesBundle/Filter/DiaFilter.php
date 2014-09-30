<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Dia;

class DiaFilter extends Dia implements ModelFilter {

	private $cicloFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new DiaFilterForm($this, $jym, '_dia');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Dia';
	}
	
	public function getSortFields() {
		return array('id'=>'Id');
	}

	
	public function getCicloFilter()
	{
		return $this->cicloFilter;
	}
	
	public function setCicloFilter($ciclo)
	{
		$this->cicloFilter = $ciclo;
	}

}
?>