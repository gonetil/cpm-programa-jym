<?php

namespace Cpm\JovenesBundle\Service;


use Cpm\JovenesBundle\StaticConfig;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\Ciclo;
use Symfony\Component\DependencyInjection\ContainerInterface;


class JYM  {
	
	private $config;
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
		$this->config = $container->getParameter("cpm_jovenes");
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
	
	public function getVariablesCorreo() {
		$variables = array(
		'{{ usuario.apellido }}, {{ usuario.nombre }}' => "Apellido, Nombre del docente destinatario",
		'{{ usuario.email }}' => "Dirección de correo del docente destinatario", 
		'{{ url_sitio }}' => "Dirección web (URL) de este sitio", 
		'{{ proyecto.escuela }}' => "Escuela del proyecto seleccionado", 
		'{{ proyecto.titulo}}' => "Título del proyecto seleccionado",
		'{{ proyecto.coordinador.apellido }}, {{ proyecto.coordinador.nombre }},' => "Apellido, Nombre del coordinador del proyecto seleccionado",
		'{{ proyecto.coordinador.email }}' => "Dirección de correo del coordinador del proyecto seleccionado",
		' {% for c in proyecto.colaboradores %} {{ c.apellido}}, {{ c.nombre}} ( {{ c.email }} ); {% endfor %}' => " Apellido, Nombre y dirección de correo de los colaboradores del proyecto"
		);
		return $variables;
	}		
	
	
	/* *********** Recuperacion de settings ********************* */
	function getParametroConfiguracion($paramName, $defaultValue = null){
		if(isset($this->config[$paramName])) 
			return $this->config[$paramName];
		else
			return $defaultValue;
	}

	public function isBloquearCiclosViejos(){
		return $this->getParametroConfiguracion('bloquear_ciclos_viejos', true);
	}
	
	public function isRegistroUsuariosAbierto(){
		return !$this->getParametroConfiguracion('bloquear_registro_usuarios', false);
	}
	
	/* *********** Autorizacion de modificacion ********************* */
	
	public function puedeEditar($targetObject, $throwException = false){
		
		if (is_null($targetObject))
			throw new \InvalidArgumentException("El targetObject no puede ser null");
		
		$user = $this->getLoggedInUser();
 
        $bloquearCiclosViejos = $this->isBloquearCiclosViejos();
		
		
				
		//CICLO, CORREO
		if(
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Ciclo || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Correo
			){
			if ($user->isSuperAdmin())//FIXME Super
				return true;
			$cause = "Necesita permisos de supervaca.";
		}
		
		//Varios
		elseif(
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Distrito || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Eje || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Localidad ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Produccion ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Tema ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\TipoEscuela ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\TipoInstitucion || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Plantilla 
			){
			if ($user->isAdmin())
				return true;
			$cause = "Necesita permisos de administrador para modificar este elemento.";
		}
		
		//COMENTARIO
		if($targetObject instanceof \Cpm\JovenesBundle\Entity\Comentario){
			if ($user->isAdmin())//FIXME validar como corresponde
				return true;
			$cause = "Necesita permisos de admin para modificar un comentario.";
		}
		
		//EVENTO, INSTANCIA EVENTO
		elseif(
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Evento ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\InstanciaEvento
		){
			if($targetObject instanceof \Cpm\JovenesBundle\Entity\InstanciaEvento)
				$targetObject = $targetObject->getEvento();
			//var_dump($bloquearCiclosViejos);die();
			if (
				(!$bloquearCiclosViejos || $targetObject->getCiclo()->getActivo()) //validacion de ciclo
				&&
				$user->isAdmin() 
			)
			return true;
		}
		
		//INVITACION
		elseif($targetObject instanceof \Cpm\JovenesBundle\Entity\Invitacion){
			if ($user->isAdmin() || $user->equals($invitacion->getProyecto()->getCoordinador()))
				return true;
			$cause = "Solo el coordinador del proyecto puede aceptar o rechazar sus invitaciones";
		}
		
		//PROYECTO
		elseif(
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Proyecto ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Escuela
		){
			if($targetObject instanceof \Cpm\JovenesBundle\Entity\Escuela)
				$targetObject = $targetObject->getProyecto();
			//var_dump($bloquearCiclosViejos);die();
			if (
				(!$bloquearCiclosViejos || $targetObject->getCiclo()->getActivo()) //validacion de ciclo
				&&
				($user->isAdmin() || $targetObject->getCoordinador()->equals($user)) 
			)
			return true;
		}

		//USUARIO
		elseif($targetObject instanceof \Cpm\JovenesBundle\Entity\Usuario){
			if ($user->isSuperAdmin() || $targetObject->equals($user) || 
				($user->isAdmin() && !$targetObject->isSuperAdmin()))
				return true;
		}
			
		if ($throwException){ 
			if (empty($cause))
				$cause = "No está habilitado para modificar este elemento (".get_class($targetObject). ")";
			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($cause);
		}
		
		return false;
	}
	
	public function getLoggedInUser($failFast = true){
		$sec = $this->container->get('security.context');
		if (!$sec->getToken())
			$cause = "No posee una sesión iniciada";
		elseif (!$sec->getToken()->isAuthenticated())
			$cause = "No posee una sesión iniciada";
		else{ 
			$user = $sec->getToken()->getUser();
			if (!$user) 
				$cause="Su sesión es inválida. Ingrese nuevamente";
			else {
				$repo = $this->doctrine->getEntityManager()->getRepository("CpmJovenesBundle:Usuario");
				return $repo->findOneByEmail($user->getUsername());
			}
		}
		if($failFast)
			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($cause);
		else
			return null; 
	}
	
	protected function isUserGranted($role, $failFast = false) { 
		$user = $this->getLoggedInUser($failFast);
		if (!$user) 
			$cause="Su sesión es inválida. Ingrese nuevamente. ";
		elseif (!$user->hasRole($role))
			$message = "No posee los permisos necesarios para realizar esta operación";
		else
			return true;
		
		if ($failFast)
			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($message);
		else
			return false;
	}
	
	
}