<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ EstadoProyecto;

class EstadoFilter extends EstadoProyecto implements ModelFilter {
	
	private $yaEvaluado;
	private $conArchivo;
	private $vigente = 1;
	private $aprobado;

    private $notas;
    public function getNotas() { return $this->notas;}
    public function setNotas($n) { $this->notas = $n; }
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new EstadoFilterForm($this, $jym, '_estado');
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
	
	public function getAprobado() {
		return $this->aprobado;
	}
	public function setAprobado($aprobado) {
		$this->aprobado = $aprobado;
	}
	
}
