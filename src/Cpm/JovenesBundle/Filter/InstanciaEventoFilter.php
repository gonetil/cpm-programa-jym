<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ InstanciaEvento;

class InstanciaEventoFilter extends InstanciaEvento implements ModelFilter {
	private $instanciaEvento; 
	private $cicloFilter;
	private $eventoFilter;
	private $confirmoInvitacionAInstancia;
	private $asistioAInstancia;
	private $rechazoInvitacionAInstancia;



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

    /**
     * @return mixed
     */
    public function getConfirmoInvitacionAInstancia()
    {
        return $this->confirmoInvitacionAInstancia;
    }

    /**
     * @param mixed $confirmoInvitacionAInstancia
     */
    public function setConfirmoInvitacionAInstancia($confirmoInvitacionAInstancia)
    {
        $this->confirmoInvitacionAInstancia = $confirmoInvitacionAInstancia;
    }

    /**
     * @return mixed
     */
    public function getAsistioAInstancia()
    {
        return $this->asistioAInstancia;
    }

    /**
     * @param mixed $asistioAInstancia
     */
    public function setAsistioAInstancia($asistioAInstancia)
    {
        $this->asistioAInstancia = $asistioAInstancia;
	}
	
	/**
     * @return mixed
     */			    
    public function getRechazoInvitacionAInstancia()
    {
        return $this->rechazoInvitacionAInstancia;
    }

    /**
     * @param mixed $rechazoInvitacionAInstancia
     */
    public function setRechazoInvitacionAInstancia($rechazoInvitacionAInstancia)
    {
        $this->rechazoInvitacionAInstancia = $rechazoInvitacionAInstancia;
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
