<?php
namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de envio de invitaciones masivas
 */

class InvitacionMasiva extends CorreoMasivo {

	private $eventos;
	private $instancias;
	
	public getEventos() 
	{
		return $this->eventos;
	}
	public setEventos($eventos)
	{
		$this->eventos = $eventos;
	}
	
	public getInstancias() {
		return $this->instancias;
	}
	public setInstancias($instancias) {
		$this->instancias = $instancias;
	}
	
}