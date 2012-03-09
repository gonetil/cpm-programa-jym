<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Usuario;
use Symfony\Component\DependencyInjection\ContainerInterface; 
class JYM {
	private static $instance;
	private $etapasPorNombre;
	private $etapas;
	private $ciclo;
	private $numeroEtapaActual;
	
	private $doctrine;
	private $logger;
	
	public static function initEtapas($etapas){
		$t=self::instance();
		$t->setEtapas($etapas);
		
	}
	
	public static function initServices($doctrine, $logger){
		$t=self::instance();
		$t->doctrine=$doctrine;
		$t->logger=$logger;
	}
	
	public static function instance(){
		if (empty(self::$instance))
			self::$instance=new self;
		elseif (!empty(self::$instance->doctrine) && !empty(self::$instance->etapas)){	
			self::$instance->lastInit();
			
		}
		return self::$instance;
	}
	
	private function __construct(){
		$this->etapas = array();
		$this->numeroEtapaActual = -1;
		$this->ciclo=null;
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
	
	/**
	 * Calcula la primer etapa y que el ciclo activo exista 
	 */
	private function lastInit(){
		$repo = $this->doctrine->getEntityManager()->getRepository("CpmJovenesBundle:Ciclo");
		
		$this->ciclo = $repo->getCicloActivo(false);
		if (empty($this->ciclo )){
			$this->logger->err("No habia ningun ciclo creado, se crea uno y se activa");
			$c = new Ciclo();
			$c->setTitulo('Ciclo 1 (Creado automaticamente)');
			$c->setActivo(true);
			$this->flush();	
		}
		$nombreEtapaActual=$this->ciclo->getEtapaActual();
		
		if (!empty($this->etapasPorNombre[$nombreEtapaActual]))
			$this->numeroEtapaActual =false;
		else 
			$this->numeroEtapaActual = $this->etapasPorNombre[$nombreEtapaActual];
		if (($this->numeroEtapaActual === false) || !$this->hasEtapa($this->numeroEtapaActual)){
			$this->gotoEtapa(0);
		}
		
		$this->logger->info("Se incializa JYM");
			
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