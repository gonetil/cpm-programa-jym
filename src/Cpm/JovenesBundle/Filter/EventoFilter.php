<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Evento;

class EventoFilter extends Evento implements ModelFilter {
	private $sinInvitacionFlag; //indica con invitacion o sin invitacion
	private $evento; 
	private $cicloFilter;
	private $asistioAlEventoFlag;
	private $confirmoInvitacionFlag;
	private $rechazoInvitacionFlag;

	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new EventoFilterForm($this, $jym, '_evento');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Evento';
	}
	
	public function getSortFields() {
		return array("id" => "Id","titulo" => "Titulo");
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
	
	public function getCicloFilter()
	{
		return $this->cicloFilter;
	}
	
	public function setCicloFilter($ciclo)
	{
		$this->cicloFilter = $ciclo;
	}

    /**
     * @return mixed
     */
    public function getConfirmoInvitacionFlag()
    {
        return $this->confirmoInvitacionFlag;
    }

    /**
     * @param mixed $confirmoInvitacionFlag
     */
    public function setConfirmoInvitacionFlag($confirmoInvitacionFlag)
    {
        $this->confirmoInvitacionFlag = $confirmoInvitacionFlag;
    }

    /**
     * @return mixed
     */
    public function getAsistioAlEventoFlag()
    {
        return $this->asistioAlEventoFlag;
    }

    /**
     * @param mixed $asistioAlEventoFlag
     */
    public function setAsistioAlEventoFlag($asistioAlEventoFlag)
    {
        $this->asistioAlEventoFlag = $asistioAlEventoFlag;
    }

    /**
     * @return mixed
     */
    public function getRechazoInvitacionFlag()
    {
        return $this->rechazoInvitacionFlag;
    }

    /**
     * @param mixed $rechazoInvitacionFlag
     */
    public function setRechazoInvitacionFlag($rechazoInvitacionFlag)
    {
        $this->rechazoInvitacionFlag = $rechazoInvitacionFlag;
    }
}
