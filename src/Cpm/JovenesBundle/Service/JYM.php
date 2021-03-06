<?php

namespace Cpm\JovenesBundle\Service;


use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\Ciclo;
use Symfony\Component\DependencyInjection\ContainerInterface;


class JYM  {
	const _PRIMER_ANIO = 2002;
	
	private $config;
	private $ciclo=null;
	private $container;
	private $doctrine;
	private $logger;
	private $perfil_dinamico;
	
	public function __construct($doctrine, $logger, ContainerInterface $container){
		$this->doctrine=$doctrine;
		$this->logger=$logger;
		$this->container=$container;
		$this->config = $container->getParameter("cpm_jovenes");
		$this->perfil_dinamico = new PerfilDinamico($this);
	} 
	
	public function getCicloActivo(){
		if (empty($this->ciclo)){
			//Calcula la primer etapa y que el ciclo activo exista 
			
			$repo = $this->doctrine->getEntityManager()->getRepository("CpmJovenesBundle:Ciclo");
			$this->ciclo = $repo->getCicloActivo(false);
			if (empty($this->ciclo )){
				//No hay ciclo activo ==> creo uno
				$c = new Ciclo();
				$c->setAnio(date('Y'));
				$c->setActivo(true);
				$c->setEtapaActual($this->getEtapaInicial());
				$this->logger->err("No habia ningun ciclo creado, se crea uno y se activa en la etapa inicial");
				
				//guardo el ciclo nuevo
				$em = $this->doctrine->getEntityManager();
				$em->persist($c);
		        $em->flush();
					
				$this->ciclo=$c;
			}
			$this->checkEtapaActiva();
			
		}
		return $this->ciclo;
	}
	
	
	public function setCicloActivo(Ciclo $cicloNuevo){
		$cicloViejo = $this->getCicloActivo();
		if ($cicloViejo->getId() == $cicloNuevo->getId())
			return;

		$cicloViejo->setActivo(false);
		$cicloNuevo->setActivo(true);
		
		$em = $this->doctrine->getEntityManager();
		$em->persist($cicloViejo);
		$em->persist($cicloNuevo);
        $em->flush();
        
        $this->logger->info("Se desactiva el ciclo ".$cicloViejo->getId(). " y se activa el ciclo ".$cicloNuevo->getId());
		$this->ciclo = $cicloNuevo;
		
		$this->checkEtapaActiva();
	}
	
	protected function checkEtapaActiva(){
		$etapaActual=$this->getEtapaActual();
		
		if (empty($etapaActual)){
			$e = new \Exception();
			$this->logger->warn("Algo raro pasa, se modifca la etapa del ciclo ".$e->getTraceAsString());
		
			$c = $this->getCicloActivo();
			$c->setEtapaActual($this->getEtapaInicial());
		
			$em = $this->doctrine->getEntityManager();
			$em->persist($c);
	        $em->flush();
		}

	}
	
	
	public function getEtapas(){
		return $this->getRepository('CpmJovenesBundle:Etapa')->findAll();
	}
	
	function getEtapaInicial(){
		return $this->getRepository('CpmJovenesBundle:Etapa')->findPrimerEtapa();
	}

	public function getEtapaAnterior(){
		return $this->getRepository('CpmJovenesBundle:Etapa')->findEtapaAnteriorA($this->getEtapaActual());	
	}

	public function getEtapaSiguiente(){
		return $this->getRepository('CpmJovenesBundle:Etapa')->findEtapaSiguienteA($this->getEtapaActual());
	}
	
	
	public function getEtapaActual(){
		return $this->getCicloActivo()->getEtapaActual();
	}
	
	public function getEventosManager(){
		return $this->container->get('cpm_jovenes_bundle.eventos_manager');
	}	

	public function getEstadosManager(){
		return $this->container->get('cpm_jovenes_bundle.estados_manager');
	}
	
	public function getVariablesCorreo() {
		$variables = array(
		'{{ usuario.apellido }}, {{ usuario.nombre }}' => "Apellido, Nombre del destinatario",
		'{{ usuario.email }}' => "Dirección de correo destinatario", 
		'{{ url_sitio }}' => "Dirección web (URL) de este sitio", 
		'{{ proyecto.escuela }}' => "Escuela del proyecto seleccionado", 
		'{{ proyecto.titulo}}' => "Título del proyecto seleccionado",
		'{{ proyecto.coordinador.apellido }}, {{ proyecto.coordinador.nombre }},' => "Apellido, Nombre del coordinador del proyecto seleccionado",
		'{{ proyecto.coordinador.email }}' => "Dirección de correo del coordinador del proyecto seleccionado",
		'{% for c in proyecto.colaboradores %} {{ c.apellido}}, {{ c.nombre}} ( {{ c.email }} ); {% endfor %}' => " Apellido, Nombre y dirección de correo de los colaboradores del proyecto",
		'{% if proyecto.estadoActual.observaciones | length > 0 %}<div>{{proyecto.estadoActual.observaciones}}</div>{% endif %}' => "Observaciones registradas en el estado del proyecto (aprobado, rehacer, etc.)"
		);
		return $variables;
	}		
	public function getVariablesCorreoUsuarios() {
		$variables = array(
		'{{ usuario.apellido }}, {{ usuario.nombre }}' => "Apellido, Nombre del destinatario",
		'{{ usuario.email }}' => "Dirección de correo destinatario", 
		'{{ url_sitio }}' => "Dirección web (URL) de este sitio"
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
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Etapa || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Correo
			){
			if ($user->isSuperAdmin())
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
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Auditorio || 
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Plantilla 
			){
			if ($user->isAdmin())
				return true;
			$cause = "Necesita permisos de administrador para modificar este elemento.";
		}
		
		//COMENTARIO
		if($targetObject instanceof \Cpm\JovenesBundle\Entity\Comentario){
			if ($user->isAdmin())
				return true;
			$cause = "Necesita permisos de admin para modificar un comentario.";
		}
		
		//EVENTO, INSTANCIA EVENTO
		elseif(
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Evento ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\InstanciaEvento
		){
			if (
				(!$bloquearCiclosViejos || $targetObject->getCiclo()->getActivo()) //validacion de ciclo
				&&
				$user->isAdmin() 
			)
			return true;
		}
		
		//INVITACION
		elseif($targetObject instanceof \Cpm\JovenesBundle\Entity\Invitacion){
			if ($user->isAdmin() || $user->equals($targetObject->getProyecto()->getCoordinador()))
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
		
		//CRONOGRAMA
		elseif($targetObject instanceof \Cpm\JovenesBundle\Entity\Bloque ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\AuditorioDia ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Dia ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Tanda ||
			$targetObject instanceof \Cpm\JovenesBundle\Entity\Presentacion
		){
			if ((!$bloquearCiclosViejos || $targetObject->getCiclo()->getActivo())
				&& $user->isSuperAdmin() || $user->isAdmin())
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
		$token = $this->container->get('security.context')->getToken();
		if (!$token)
			$cause = "No posee una sesión iniciada";
		elseif (!$token->isAuthenticated())
			$cause = "No posee una sesión iniciada";
		elseif ($token instanceof \Symfony\Component\Security\Core\Authentication\Token\AnonymousToken)
			$cause = "Sesión anónima";
		else{ 
			$user = $token->getUser();
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
	
	public function isUserGranted($role, $failFast = false) { 
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
	
	/**
	 * Devuelve un array con todos los años en los que podría haber participado un usuario (checkboxes aniosParticipo y usuarioFilter)
	 */
	public function getRangoDeAnios() {
		$anios = array();
		
		for($i=JYM::_PRIMER_ANIO;$i<date('Y');$i++) {
			$anios[$i] = $i;
		}
		return $anios;
	}

	protected function getRepository($fqcn){
		return $this->doctrine->getEntityManager()->getRepository($fqcn);
	} 


	public function getPerfil(){
		return $this->perfil_dinamico;
	}
	
	public function getUploadDir() 
    {
    	$dir = $this->container->getParameter('upload_dir');
    	if (substr($dir,strlen($dir)-1,1) != "/")
    		$dir .= "/";
    	return $dir;
    }
    
    public function getURLSitio($removeBackSlash=true) {
    	$url = $this->container->getParameter('jym_url_sitio');
    	return ($removeBackSlash) ? substr($url,0,strlen($url)-1) : $url;
    	 
    }
		
}
