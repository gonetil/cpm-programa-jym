<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Invitacion;

class InvitacionFilter extends Invitacion implements ModelFilter {

	private $fechaMin;
	private $fechaMax;
	private $coordinador;
	private $cicloFilter;
	
	public function createForm(Cpm\JovenesBundle\Service\JYM $jym){
		return new InvitacionFilterForm($this, $jym);
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Invitacion';
	}
	
	public function getSortFields() {
		return array("c.id" => "Id","c.fechaCreacion" => "Fecha envio");
	}
	
	public function getFechaMin(){
		return $this->fechaMin;	
	}
	
	public function setFechaMin($fechaMin){
		$this->fechaMin=$fechaMin;	
	}
	
	public function getFechaMax(){
		return $this->fechaMax;
	}

	public function setFechaMax($fechaMax){
		$this->fechaMax=$fechaMax;
	}
	
	public function getCoordinador() {
		return $this->coordinador;
	}
	public function setCoordinador($coordinador) {
		$this->coordinador = $coordinador;
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