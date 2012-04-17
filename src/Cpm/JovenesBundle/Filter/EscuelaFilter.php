<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Escuela;

class EscuelaFilter extends Escuela implements ModelFilter {

	public $regionDesde;
	public $regionHasta;

	public function createForm() {
		return new EscuelaFilterForm($this, '_escuela');
	}

	public function getTargetEntity() {
		return 'CpmJovenesBundle:Escuela';
	}
	public function getRegionDesde() {
		return $this->regionDesde;
	}

	public function getRegionHasta() {
		return $this->regionHasta;
	}

}