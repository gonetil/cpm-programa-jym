<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Usuario;

class JovenesYMemoria {
	
	private $etapas;
	private $ciclo;
	private $numeroEtapaActual;
	private $accionesUsuario;
	private $accionesProyecto;
	
	private $doctrine;
	private $logger;
	
	public function __construct($doctrine, $logger){
		$this->etapas = array();
		$this->numeroEtapaActual = array();
		$this->doctrine=$doctrine;
		$this->logger=$logger;
		$this->ciclo=null;
	} 
	
	private function setEtapas($etapas, $accionesUsuarios, $accionesProyecto){
		$this->etapas = $etapas;
		$this->accionesUsuario = array();
		$this->accionesProyecto = array();
		foreach ( $tapas as $numEtapa => $nombreEtapa) {
			if (empty($accionesUsuarios[$numEtapa]))
				$accionesUsuarios[$numEtapa]=array();
			$this->accionesUsuario[$numEtapa]=$accionesUsuarios[$numEtapa];
			
			if (empty($accionesProyecto[$numEtapa]))
				$accionesProyecto[$numEtapa]=array();
			$this->accionesProyecto[$numEtapa]=$accionesProyecto[$numEtapa];
		}
		$this->logger->info("Se cargan las etapas y sus acciones");
		$this->calcularEtapaActual();
	}
	
	private function calcularEtapaActual(){
		$repo = $this->doctrine->getEntityManager()->getRepository("CpmJovenesBundle:Ciclo");
		
		$this->ciclo = $repo->getCicloActivo(false);
		if (empty($this->ciclo )){
			$this->logger->err("No habia ningun ciclo creado, se crea uno y se activa");
			$c = new Ciclo();
			$c->setTitulo('Ciclo 1 (Creado automaticamente)');
			$c->setActivo(true);
			$this->flush();	
		}
		
		$etapaActualNombre = $this->ciclo->getEtapaActual();
		$this->numeroEtapaActual = array_search($etapaActualNombre, $this->etapas);
		if ($this->numeroEtapaActual ===false){
			$this->gotoEtapa(0);
		}
	}
	
	private function flush(){
		$em = $this->doctrine->getEntityManager();
		$em->persist($this->ciclo);
        $em->flush();
	}
	
	public function gotoEtapaSiguiente(){
		$this->gotoEtapa($this->numeroEtapaActual+1);
	}

	public function gotoEtapaAnterior(){
		$this->gotoEtapa($this->numeroEtapaActual-1);
	}

	public function hasEtapaSiguiente(){
		return $this->hasEtapa($this->numeroEtapaActual+1);
	}

	public function hasEtapaAnterior(){
		return $this->hasEtapa($this->numeroEtapaActual-1);
	}

	protected function hasEtapa($numeroEtapa){
		return !empty($this->etapas[$numeroEtapa]);
	}
	
	protected function gotoEtapa($numeroEtapa){
		if (!$this->hasEtapa($numeroEtapa)){
			$this->logger->err("No existe la etapa ".$numeroEtapa);
			throw new \OutOfRangeException("No existe la etapa ".$numeroEtapa);
		}
		
		$this->numeroEtapaActual=$numeroEtapa;
		
		$nombreEtapaActual=$this->etapas[$this->numeroEtapaActual]
		$this->ciclo->setEtapaActual($nombreEtapaActual);
		$this->flush();
	}
	
	public function getEtapaActual(){
		return $this->etapas[$this->numeroEtapaActual];
	}
	
	public function getUserActions(Usuario $usuario){
		return $this->accionesUsuario[$this->numeroEtapaActual];
	}
	
	public function getProjectoActions(Project $project){
		return $this->accionesProyecto[$this->numeroEtapaActual];
	}
}