<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Evento;

class EventoFilter extends Evento implements ModelFilter {
	private $sinInvitacionFlag; //indica con invitacion o sin invitacion
	private $evento; 
	
	public function createForm() {
		return new EventoFilterForm($this, '_evento');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Evento';
	}
	
	public function getSinInvitacionFlag() { 
		return $this->sinInvitacionFlag;
	}
	public function setSinInvitacionFlag($flag) {
		$this->sinInvitacionFlag = $flag;
	}
	
	public function getEvento() { 
		return $this->evento;
	}
	
	public function setEvento($evento) { 
		$this->evento = $evento;
	}
	
}
