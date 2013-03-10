<?php

namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Ciclo;

class CicloFilter extends Ciclo implements ModelFilter
{
	public $ciclo = null;
	public $jym;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		$this->jym = $jym;
		return new CicloFilterForm($this,$jym, "_ciclo");
	}
	
	public function getTargetEntity(){
		return 'CpmJovenesBundle:Ciclo';
	}

	public function getSortFields() {
		return array(); 
	}
	
	public function getCiclo(){
		return $this->ciclo;	
	}
	
	public function setCiclo($ciclo) {
		return $this->ciclo = $ciclo;
	}
	
	public function getCicloActivo() {
		return $this->jym->getCicloActivo();
	}
}	