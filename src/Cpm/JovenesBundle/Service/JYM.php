<?php

namespace Cpm\JovenesBundle\Service;


use Cpm\JovenesBundle\StaticConfig;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\Ciclo;
use Symfony\Component\DependencyInjection\ContainerInterface;


class JYM  {
	private $etapasPorNombre;
	private $etapas;
	private $ciclo;
	private $numeroEtapaActual;
	private $container;
	private $doctrine;
	private $logger;
	private $perfil_dinamico;

	public function __construct($doctrine, $logger, ContainerInterface $container){
		$this->doctrine=$doctrine;
		$this->logger=$logger;
		$this->container=$container;
		
		$this->setEtapas(StaticConfig::getEtapas());
		$this->ciclo=null;
		$this->lastInit();
		$this->perfil_dinamico = new PerfilDinamico();
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
	
	public function getDescripcionEtapa($numeroEtapa) { 
		if (!$this->hasEtapa($numeroEtapa)){
			throw new \OutOfRangeException("No existe la etapa ".$numeroEtapa);
		}
		return $this->etapas[$numeroEtapa]['descripcion'];
	}	
	
	public function getDescripcionEtapaActual(){
		return $this->getDescripcionEtapa($this->numeroEtapaActual);
	}

	public function getProyectoActualFilterEtapaActual() { 
		return $this->getProyectoActualFilterEtapa($this->numeroEtapaActual);
	}
	
	public function getProyectoActualFilterEtapa($numeroEtapa) {
		if (!$this->hasEtapa($numeroEtapa)){
			throw new \OutOfRangeException("No existe la etapa ".$numeroEtapa);
		}
		
		for($i = $numeroEtapa; $i >=0; $i-- ) {  //busco hacia atrás la primer etapa que defina el filtro
			if (isset( $this->etapas[$i]['proyectos_activos_filter']))
				return $this->etapas[$i]['proyectos_activos_filter'];		
		}	
		return '';		
	}
	
	public function getEtapaActual(){
		if (empty($this->etapas[$this->numeroEtapaActual]))
			$this->gotoEtapa(0);
		return $this->etapas[$this->numeroEtapaActual];
	}
	
	public function getAccionesUsuario(){
		
		$usuario = $this->container->get('security.context')->getToken()->getUser();
		return $this->perfil_dinamico->accionesDeUsuario($usuario,$this->etapas[$this->numeroEtapaActual]);
	}
	
	public function getAccionesProjecto(Proyecto $proyecto){
	
		$usuario = $this->container->get('security.context')->getToken()->getUser();
		return $this->perfil_dinamico->accionesDeProyecto($proyecto,$usuario,$this->etapas[$this->numeroEtapaActual]);
	}
	
	public function mensajesDeUsuario() {
		$usuario = $this->container->get('security.context')->getToken()->getUser();
		return $this->perfil_dinamico->mensajesDeUsuario($usuario,$this->etapas[$this->numeroEtapaActual]);
	}
	public function mensajesDeProyecto(Proyecto $proyecto) {
		$usuario = $this->container->get('security.context')->getToken()->getUser();
		return $this->perfil_dinamico->mensajesDeProyecto($proyecto,$usuario,$this->etapas[$this->numeroEtapaActual]);
	}
	
	public function getNombresEtapas(){
		return $this->etapas;
	}
	
	public function getEventosManager(){
		return $this->container->get('cpm_jovenes_bundle.eventos_manager');
	}	

	public function getEstadosManager(){
		return $this->container->get('cpm_jovenes_bundle.estados_manager');
	}		
}