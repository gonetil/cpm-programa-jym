<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Usuario;

class UsuarioFilter extends Usuario implements ModelFilter {

	private $apellido;
	protected $email;
	private $habilitados;
	private $soloCoordinadores;	
	private $cicloFilter;
	private $ciclo;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new UsuarioFilterForm($this, $jym);
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Usuario';
	}
	
	public function getSortFields() {
		return array("c.id" => "Id","c.apellido" => "Apellido");
	}
	
	public function getApellido() {
		return $this->apellido;
	}
	public function setApellido($apellido) 
	{
		$this->apellido = $apellido;
	}
	
	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getHabilitados() {
		return $this->habilitados;
	}
	public function setHabilitados($habilitados) {
		$this->habilitados = $habilitados;
	}
	
	public function getSoloCoordinadores() {
		return $this->soloCoordinadores;
	}
	public function setSoloCoordinadores($soloCoordinadores) {
		$this->soloCoordinadores = $soloCoordinadores;
	}
	
	public function getCiclo() {
		return $this->ciclo;
	}
	public function setCiclo($ciclo) {
		$this->ciclo = $ciclo;
	}
	
	public function getCicloFilter()
	{ 
		if (!$this->cicloFilter)
			$this->cicloFilter = new CicloFilter();
			
		return $this->cicloFilter;
	}
	
	public function setCicloFilter($ciclo)
	{
		$this->cicloFilter = $ciclo;
	}
	
}