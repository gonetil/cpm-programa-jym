<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Tanda;

class TandaFilter extends Tanda implements ModelFilter {

	private $cicloFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new TandaFilterForm($this, $jym, '_tanda');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Tanda';
	}
	
	public function getSortFields() {
		return array("id" => "Id");
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