<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ InstanciaEvento;

class InstanciaEventoFilter extends InstanciaEvento implements ModelFilter {
	private $instanciaEvento; 
	private $cicloFilter;
	private $eventoFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new InstanciaEventoFilterForm($this, $jym, '_instancia_evento');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:InstanciaEvento';
	}
	
	public function getInstanciaEvento() {
		return $this->instanciaEvento;
	}
	
		
	public function getSortFields() {
		return array("id" => "Id"); //,"titulo" => "Titulo");
	}
	
	public function setInstanciaEvento($instancia) {
		$this->instanciaEvento = $instancia;
	}
	
	public function getCicloFilter()
	{ 
		return $this->cicloFilter;
	}
	
	public function setCicloFilter($ciclo)
	{
		$this->cicloFilter = $ciclo;
	}
	
	public function getEventoFilter()
	{
		return $this->eventoFilter;
	}
	
	public function setEventoFilter($evento)
	{
		$this->eventoFilter = $evento;
	}
}
