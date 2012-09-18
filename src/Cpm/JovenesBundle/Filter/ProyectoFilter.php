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

	public function createForm() {
		return new ProyectoFilterForm($this);
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

}