<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ InstanciaEvento;

class InstanciaEventoFilter extends InstanciaEvento implements ModelFilter {
	private $instanciaEvento; 
	
	public function createForm() {
		return new InstanciaEventoFilterForm($this, '_instancia_evento');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:InstanciaEvento';
	}
	
	public function getInstanciaEvento() {
		return $this->instanciaEvento;
	}
	
	public function setInstanciaEvento($instancia) {
		$this->instanciaEvento = $instancia;
	}
	
}
