<?php

namespace Cpm\JovenesBundle\Service;


use Cpm\JovenesBundle\StaticConfig;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\Ciclo;
use Symfony\Component\DependencyInjection\ContainerInterface; 

class JYM {
	private $etapasPorNombre;
	private $etapas;
	private $ciclo;
	private $numeroEtapaActual;
	
	private $doctrine;
	private $logger;
/*
	public static function instance(){
		if (empty(self::$instance)){
			throw new \IllegalStateException("No existe una instancia de JYM");
		}
		return self::$instance;
	}
	
	public static function initServices($doctrine, $logger){
		if (!empty(self::$instance)){
			throw new \IllegalStateException("Ya existe una instancia de JYM");
		}
		self::$instance=new JYM($doctrine, $logger);
		self::$instance->lastInit();
		
	}
	*/
	public function __construct($doctrine, $logger){
		$this->doctrine=$doctrine;
		$this->logger=$logger;
		$this->setEtapas(StaticConfig::getEtapas());
		$this->ciclo=null;
		$this->lastInit();
	} 
	
	private function setEtapas($etapas){
		$this->etapasPorNombre = array();
		$this->etapas = $etapas;
		foreach ( $this->etapas as $numEtapa => $sarassaasa) {
			$this->etapasPorNombre[$this->etapas[$numEtapa]['nombre']]=$numEtapa;
			
			if (empty($this->etapas[$numEtapa]['accionesUsuario']))
				$this->etapas[$numEtapa]['accionesUsuario']=array();

			if (empty($this->etapas[$numEtapa]['accionesProyectos']))
				$this->etapas[$numEtapa]['accionesProyectos']=array();
		}
	}
	
	public function getCicloActivo(){
		return $this->ciclo;
	}
	
	/**
	 * Calcula la primer etapa y que el ciclo activo exista 
	 */
	private function lastInit(){
		$repo = $this->doctrine->getEntityManager()->getRepository("CpmJovenesBundle:Ciclo");
		
		$this->ciclo = $repo->getCicloActivo(false);
		if (empty($this->ciclo )){
			$this->logger->err("No habia ningun ciclo creado, se crea uno y se activa");
			$this->ciclo = new Ciclo();
			$this->ciclo->setTitulo('Ciclo 1 (Creado automaticamente)');
			$this->ciclo->setActivo(true);
			$this->gotoEtapa(0);
		}
		$nombreEtapaActual=$this->ciclo->getEtapaActual();
		if (!isset($this->etapasPorNombre[$nombreEtapaActual]))
			$this->numeroEtapaActual = false;
		else 
			$this->numeroEtapaActual = $this->etapasPorNombre[$nombreEtapaActual];
		//echo "busco la etapa ".$this->etapasPorNombre[$nombreEtapaActual];
		if (($this->numeroEtapaActual === false) || !$this->hasEtapa($this->numeroEtapaActual)){
			$this->gotoEtapa(0);
		}
		
		$this->logger->info("Se incializa JYM");
			
	}
	
	public function setCicloActivo(Ciclo $ciclo){
		if ($this->ciclo->getId() == $ciclo->getId())
			return;

		$this->ciclo->setActivo(false);
		$ciclo->setActivo(true);
		
		$em = $this->doctrine->getEntityManager();
		$em->persist($this->ciclo);
		$em->persist($ciclo);
        $em->flush();
        
        $this->logger->info("Se desactiva el ciclo ".$this->ciclo->getId(). " y se activa el ciclo ".$ciclo->getId());
		$this->ciclo = $ciclo;
		$this->gotoEtapa(0);
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
		
		$nombreEtapaActual=$this->etapas[$this->numeroEtapaActual]['nombre'];
		$this->ciclo->setEtapaActual($nombreEtapaActual);
		$this->flush();
	}
	public function getNombreEtapaActual(){
		return $this->getNombreEtapa($this->numeroEtapaActual);
	}

	public function getNombreEtapa($numeroEtapa){
		if (!$this->hasEtapa($numeroEtapa)){
			throw new \OutOfRangeException("No existe la etapa ".$numeroEtapa);
		}
		return $this->etapas[$numeroEtapa]['nombre'];
	}	
	
	public function getEtapaActual(){
		if (empty($this->etapas[$this->numeroEtapaActual]))
			$this->gotoEtapa(0);
		return $this->etapas[$this->numeroEtapaActual];
	}
	
	public function getAccionesUsuario(){
		$acciones = array();
		foreach ( $this->etapas[$this->numeroEtapaActual]['accionesUsuario'] as $key => $accion ) {
			//if (eval($accion['condition']))
			$acciones[]=$accion;
		}
		return $acciones;
	}
	
	public function getAccionesProjecto(Proyecto $proyecto){
		$acciones = array();
		foreach ( $this->etapas[$this->numeroEtapaActual]['accionesProyecto'] as $key => $accion ) {
			//if (eval($accion['condition'] on $project))
			$acciones[]=$accion;
		}
		return $acciones;
	}
	
	public function getNombresEtapas(){
		return $this->etapas;
	}
	
}