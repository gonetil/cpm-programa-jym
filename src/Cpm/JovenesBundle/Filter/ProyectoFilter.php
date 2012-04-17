<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Proyecto;

/**
 * Formulario de busqueda y filtrado de proyctos
 */
class ProyectoFilter extends Proyecto implements ModelFilter {
	private $escuelaFilter;

	public function createForm() {
		return new ProyectoFilterForm($this);
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Proyecto';
	}

	public function getEscuelaFilter() {
		if (!$this->escuelaFilter)
			$this->escuelaFilter=new EscuelaFilter();
		return $this->escuelaFilter;
	}

	public function setEscuelaFilter($escuelaFilter) {
		$this->escuelaFilter = $escuelaFilter;
	}

}