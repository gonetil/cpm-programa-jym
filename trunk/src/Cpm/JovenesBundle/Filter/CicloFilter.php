<?php

namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Ciclo;

class CicloFilter extends Ciclo implements ModelFilter
{
	public $ciclo = null;
	
	public function createForm(){
		return new CicloFilterForm($this, "_ciclo");
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