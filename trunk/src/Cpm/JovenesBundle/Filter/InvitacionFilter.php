<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Invitacion;

class InvitacionFilter extends Invitacion implements ModelFilter {

	private $fechaMin;
	private $fechaMax;
	
	public function createForm() {
		return new InvitacionFilterForm($this);
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Invitacion';
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