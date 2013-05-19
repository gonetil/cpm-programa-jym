<?php

namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Archivo;

class ArchivoFilter extends Archivo implements ModelFilter
{
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new ArchivoFilterForm($this,$jym, "_archivo");
	}
	
	public function getTargetEntity(){
		return 'CpmJovenesBundle:Archivo';
	}

	public function getSortFields() {
		return array(); 
	}
	
}	