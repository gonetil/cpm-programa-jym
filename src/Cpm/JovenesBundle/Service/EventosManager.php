<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Plantilla;
use FOS\UserBundle\Model\UserInterface;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Invitacion;
use Cpm\JovenesBundle\Entity\Evento;
use Cpm\JovenesBundle\Entity\InstanciaEvento;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\EntityDummy\InvitacionBatch;



/**
 */
class EventosManager
{
	protected $mailer;
	protected $jym;
    protected $doctrine;
    protected $logger;
    private $correoCoordinadorMaster;
    private $correoEscuelaMaster;
    private $correoColaboradoresMaster;

    public function __construct(TwigSwiftMailer $mailer, JYM $jym, $doctrine, $logger)
    {
        $this->mailer = $mailer;
        $this->jym = $jym;
	    $this->doctrine = $doctrine;
	    $this->logger = $logger;
	    
	    $this->correoCoordinadorMaster = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO);
	    $this->correoEscuelaMaster = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_ESCUELA);
		$this->correoColaboradoresMaster = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_COLABORADORES);
		
//    throw new \InvalidArgumentException("No existe la plantilla ");
}
    
	public function getInstanciasDisponibles(){
		$ir = $this->doctrine->getRepository('CpmJovenesBundle:InstanciaEvento');
		return $ir->getInstanciasVigentes($this->jym->getCicloActivo());
	}

	public function getInvitacion(InstanciaEvento $instancia, Proyecto $proyecto){
		
		$ir = $this->doctrine->getRepository('CpmJovenesBundle:Invitacion');
		return $ir->findOneBy(array('instanciaEvento'=>$instancia->getId(), 'proyecto'=>$proyecto->getId()));
	}
    
 	public function invitarProyectos(InvitacionBatch $invitacionBatch){
 		//TODO validar permisos del usuario logueado?
    	$instancia = $invitacionBatch->getInstancia();
    	$ccEscuela=$invitacionBatch->getCcEscuelas();
    	$ccColaboradores=$invitacionBatch->getCcColaboradores();
    	$avoidMail = $invitacionBatch->getNoEnviarCorreo();

    	//set_time_limit(60+3*count($invitacionBatch->getProyectos()));
        set_time_limit(0);
    	$sin_enviar = array();
        foreach ( $invitacionBatch->getProyectos() as $p ) {
			 list($invitacion,$enviada) = $this->invitarProyecto($instancia, $p,$ccEscuela,$ccColaboradores,$avoidMail);
			 if (!$enviada) { 
			 	$sin_enviar[] = $p;
			 }
		}
		return $sin_enviar;
    }
    public function enviarInvitacionAProyecto($invitacion,$ccEscuela=false,$ccColaboradores=false){
    	$p = $invitacion->getProyecto();
		
    	$context=array(Plantilla::_INVITACION => $invitacion);
		$asunto = "Invitación a Evento: " . $invitacion->getInstanciaEvento()->getEvento()->getTitulo();
		if ($ccEscuela){
			$correoEscuela = $this->correoEscuelaMaster->clonar(false);
			$correoEscuela->setProyecto($p);
			$correoEscuela->setAsunto($asunto);	
			$this->mailer->enviarCorreoAEscuela($correoEscuela, $context);
			unset($correoEscuela);
		}
		if ($ccColaboradores){
			$correoColaborador = $this->correoColaboradoresMaster->clonar(false);
			$correoColaborador->setProyecto($p);
			$correoColaborador->setAsunto($asunto);		
			$this->mailer->enviarCorreoAColaboradores($correoColaborador, $context);
			unset($correoColaborador);
		}
		$context[Plantilla::_URL]=$this->mailer->resolveUrlParameter('abrir_invitacion', array('id'=>$invitacion->getId(), 'accion'=>'aceptar'));
		$correoCoordinador = $this->correoCoordinadorMaster->clonar(false);
		$correoCoordinador->setProyecto($p);
		$correoCoordinador->setAsunto($asunto);
		$this->mailer->enviarCorreoACoordinador($correoCoordinador, $context);
		unset($correoCoordinador);
		
		unset($p);
		unset($invitacion);
		unset($context);
    }

	public function invitarProyecto($instancia, $p,$ccEscuela,$ccColaboradores,$avoidMail){
		$invitacion = $this->getInvitacion($instancia, $p);
		if (empty($invitacion)){
			    $this->logger->info("Se invita al proyecto '".$p->getId()."' al evento '".$instancia->getTitulo()."'");
		
				$invitacion = new Invitacion();
				$invitacion->setInstanciaEvento($instancia);
				$invitacion->setProyecto($p);
				
				$em = $this->doctrine->getEntityManager();
		        $em->persist($invitacion);
		        $em->flush();

				if (! $avoidMail)
			 		$this->enviarInvitacionAProyecto($invitacion,$ccEscuela,$ccColaboradores);
			 	
		}else{
				$this->logger->info("Ya exise una invitacion para el proyecto '".$p->getId()."' al evento '".$instancia->getTitulo()."', no se hace nada.");
				return array($invitacion,false);
		}
        return array($invitacion,true);
	}
	
	public function reinvitarProyectos($instancia,$ccEscuela,$ccColaboradores) {
		
	//	$memoInitZero = memory_get_usage();
    //	echo "comenzamos con $memoInitZero <br>";
    	
		$invitaciones = $this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesPendientes($instancia);
        
    	set_time_limit(60+3*count($invitaciones));
    	$i = 0;
    //	$memoInit = memory_get_usage();
    //	echo "el init se comio :".($memoInit - $memoInitZero);
      	$em = $this->doctrine->getEntityManager();
		$batchSize = 20;
    	foreach ($invitaciones as $invitacion) {
    //		$memo = memory_get_usage();
    		$this->enviarInvitacionAProyecto($invitacion[0], $ccEscuela,$ccColaboradores);
    		//$this->doctrine->getEntityManager()->clear();
    		if (($i++ % $batchSize) == 0) {
		        $em->flush();
		        $em->clear(); // Detaches all objects from Doctrine!
		    }
  //  		echo "envio ".$i. ", termino con delta mem".(memory_get_usage() - $memo)."<br>";
    		
		}
		
//		echo "finalizo con ".(memory_get_usage() - $memoInit);
		return $i;
	}
	
	public function getReporteInvitaciones(InstanciaEvento $instancia){
		
		$res = $this->doctrine->getRepository('CpmJovenesBundle:Invitacion')->getCantidadesPorInstancia($instancia);
		
		return $res;
	}
	
}
