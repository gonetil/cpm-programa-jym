<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ EstadoProyecto;

class EstadoFilter extends EstadoProyecto implements ModelFilter {
	
	private $yaEvaluado;
	private $conArchivo;
	private $vigente = 1;
	private $nota;
	
	public function createForm() {
		return new EstadoFilterForm($this, '_estado');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:EstadoProyecto';
	}
	
	public function getYaEvaluado() {
		return $this->yaEvaluado;
	} 
	public function setYaEvaluado($yaEvaluado) {
		$this->yaEvaluado = $yaEvaluado;
	}
	
	public function getConArchivo() {
		return $this->conArchivo;
	}
	
	public function setConArchivo($conArchivo) {
		$this->conArchivo = $conArchivo;
	}
	
	public function getVigente() {
		return $this->vigente;
	}
	
	public function setVigente($vigente) {
		$this->vigente = $vigente;
	}
	
	public function getNota() {
		return $this->nota;
	}
	
	public function setNota($nota) {
		$this->nota = $nota;
	}

}
