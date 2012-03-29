<?php
namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de envio de invitaciones masivas
 */

class InvitacionBatch extends ProyectoBatch{

	private $evento;
	private $instancia;
	public $ccColaboradores;
	public $ccEscuelas;


	public function __construct() {
		parent::__construct();
		$this->ccEscuelas = false;
		$this->ccColaboradores = false;
		$this->evento = null;
		$this->instancia = null;
	}	
	
	public function getEvento() 
	{
		return $this->evento;
	}
	
	public function setEvento($evento)
	{
		$this->evento = $evento;
	}
	
	public function getInstancia() {
		return $this->instancia;
	}
	
	public function setInstancia($instancia) {
		$this->instancia = $instancia;
	}

	public function getCcColaboradores() {
		return $this->ccColaboradores ;
	}
	public function setCcColaboradores($x) {
		$this->ccColaboradores = $x;
	}
	public function getCcEscuelas() {
		return $this->ccEscuelas ;
	}
	public function setCcEscuelas($x) {
		$this->ccEscuelas = $x;
	}	
}