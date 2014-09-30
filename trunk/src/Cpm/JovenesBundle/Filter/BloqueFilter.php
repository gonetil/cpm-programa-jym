<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Bloque;

class BloqueFilter extends Bloque implements ModelFilter {

	private $cicloFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new BloqueFilterForm($this, $jym, '_bloque');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Bloque';
	}
	
	public function getSortFields() {
		return array('numero'=>'Número');
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