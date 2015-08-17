<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Proyecto;

/**
 * Formulario de busqueda y filtrado de proyctos
 */
class ProyectoFilter extends Proyecto implements ModelFilter {
	private $escuelaFilter;
	private $eventoFilter;
	private $estadoFilter;
	private $instanciaEventoFilter;
	private $cicloFilter;
	private $usuarioFilter;

    private $temasPricipales; //usado para la seleccion de multiples temas
    public function getTemasPrincipales() { return $this->temasPricipales; }
    public function setTemasPrincipales($temas) { $this->temasPricipales = $temas; }

    private $ejes;
    public function getEjes() { return $this->ejes; }
    public function setEjes($e) { $this->ejes = $e; }

    private $produccionesFinales;
    public function getProduccionesFinales() { return $this->produccionesFinales; }
    public function setProduccionesFinales($pf) { $this->produccionesFinales = $pf;}


	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new ProyectoFilterForm($this, $jym);
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Proyecto';
	}
	
	public function getSortFields() {
		return array("id" => "Id","titulo" => "Titulo","coordinador"  => "Apellido Coordinador");
	}
	
	public function getEscuelaFilter() {
		if (!$this->escuelaFilter)
			$this->escuelaFilter=new EscuelaFilter();
		return $this->escuelaFilter;
	}

	public function setEscuelaFilter($escuelaFilter) {
		$this->escuelaFilter = $escuelaFilter;
	}

	public function getEventoFilter() {
		if (!$this->eventoFilter)
			$this->eventoFilter=new EventoFilter();
		return $this->eventoFilter;
	}

	public function setEventoFilter($eventoFilter) {
		$this->eventoFilter = $eventoFilter;
	}
	
	public function getEstadoFilter() {
		if (!$this->estadoFilter)
			$this->estadoFilter = new EstadoFilter();
		return $this->estadoFilter;
	}
	public function setEstadoFilter($estadoFilter) {
		$this->estadoFilter = $estadoFilter;
	}
	
	public function getInstanciaEventoFilter() {
		if (!$this->instanciaEventoFilter)
			$this->instanciaEventoFilter = new InstanciaEventoFilter();
		return $this->instanciaEventoFilter;	
	}
	public function setInstanciaEventoFilter($instanciaEventoFilter) {
		$this->instanciaEventoFilter = $instanciaEventoFilter;
	}

	public function setCicloFilter($cicloFilter) {
		$this->cicloFilter = $cicloFilter;
	}
	
	public function getCicloFilter() {
		if (!$this->cicloFilter)
			$this->cicloFilter = new CicloFilter();
			
		return $this->cicloFilter;
	}
	
	public function getUsuarioFilter() {
		if (!$this->usuarioFilter)
			$this->usuarioFilter = new UsuarioFilter();
		return $this->usuarioFilter;
	}
	public function setUsuarioFilter($usuarioFilter) {
		$this->usuarioFilter = $usuarioFilter;
	}
}