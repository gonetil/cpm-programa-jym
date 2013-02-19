<?php

namespace Cpm\JovenesBundle\Filter;

use Cpm\JovenesBundle\Entity\Correo;

class CorreoFilter extends Correo implements ModelFilter
{
	private $fechaMin;
	private $fechaMax;
	
	public function createForm(Cpm\JovenesBundle\Service\JYM $jym){
		return new CorreoFilterForm($this,$jym);
	}
	public function getTargetEntity(){
		return 'CpmJovenesBundle:Correo';
	}

	public function getSortFields() {
		return array("c.id" => "Id","c.fecha" => "Fecha envio");
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


}
