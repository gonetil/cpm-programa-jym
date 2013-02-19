<?php

namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Ciclo;

class CicloFilter extends Ciclo implements ModelFilter
{
	public $ciclo = null;
	
	public function createForm(Cpm\JovenesBundle\Service\JYM $jym){
		return new CicloFilterForm($this,$jym, "_ciclo");
	}
	
	public function getTargetEntity(){
		return 'CpmJovenesBundle:Ciclo';
	}

	public function getSortFields() {
		return array(); //"c.id" => "Id","c.titulo" => "Titulo"
	}
	
	public function getCiclo(){
		return $this->ciclo;	
	}
	
	public function setCiclo($ciclo) {
		return $this->ciclo = $ciclo;
	}
	
}	