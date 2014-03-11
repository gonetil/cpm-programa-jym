<?php
/*
 * Created on Mar 11, 2014
 * @author gonetil
 * project jym
 * Copyleft 2014
 * 
 */
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Voluntario;

class VoluntarioFilter extends Voluntario implements ModelFilter {
		
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new VoluntarioFilterForm($this, $jym, '_voluntario');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Voluntario';
	}
	
	public function getSortFields() {
		return array("id" => "Id","apellido" => "Apellido");
	}
	
}
