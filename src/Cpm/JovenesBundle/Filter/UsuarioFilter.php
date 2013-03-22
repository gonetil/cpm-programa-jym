<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Usuario;

class UsuarioFilter extends Usuario implements ModelFilter {

	private $apellido;
	protected $email;
	private $habilitados;
	private $soloCoordinadores;
	private $primeraVezQueParticipa; //o sea, aniosParticio = NULL o {}	
	private $sinProyectosEsteCiclo;

	private $ciclo;
	private $aniosParticipo;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new UsuarioFilterForm($this, $jym,'_usuario');
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
	public function getAniosParticipo() {
		return $this->aniosParticipo;
	}
	public function setAniosParticipo($anios) {
		$this->aniosParticipo = $anios;
	}
	
	public function getPrimeraVezQueParticipa() {
		return $this->primeraVezQueParticipa;
	}
	
	public function setPrimeraVezQueParticipa($aBool) {
		$this->primeraVezQueParticipa = $aBool;
	}
	
	public function getSinProyectosEsteCiclo() {
		return $this->sinProyectosEsteCiclo;
	}
	
	public function setSinProyectosEsteCiclo($aBool) {
		$this->sinProyectosEsteCiclo = $aBool;
	}
	
	
/*	el principio el usuarioFilter ya no usa más el cicloFilter
 * private $cicloFilter;
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
	*/
}