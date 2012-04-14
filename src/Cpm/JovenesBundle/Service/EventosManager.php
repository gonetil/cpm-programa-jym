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

    public function __construct(TwigSwiftMailer $mailer, JYM $jym, $doctrine, $logger)
    {
        $this->mailer = $mailer;
        $this->jym = $jym;
	    $this->doctrine = $doctrine;
	    $this->logger = $logger;
//    throw new \InvalidArgumentException("No existe la plantilla ");
}
    
	public function getInstanciasDisponibles(){
		$ir = $this->doctrine->getRepository('CpmJovenesBundle:InstanciaEvento');
		return $ir->getInstanciasVigentes($this->jym->getCicloActivo());
	}

	public function getInvitacion(InstanciaEvento $instancia, Proyecto $proyecto){
		$ir = $this->doctrine->getRepository('CpmJovenesBundle:Invitacion');
		return $ir->findOneBy(array('instanciaEvento'=>$instancia, 'proyecto'=>$proyecto));
	}
    
 	public function invitarProyectos(Usuario $admin, InvitacionBatch $invitacionBatch){
 		
    	$instancia = $invitacionBatch->getInstancia();
    	
    	$ccEscuela=$invitacionBatch->getCcEscuelas();
    	$ccColaboradores=$invitacionBatch->getCcColaboradores();
    	
        foreach ( $invitacionBatch->getProyectos() as $p ) {
			 $invitacion = $this->invitarProyecto($instancia, $p,$ccEscuela,$ccColaboradores);
		}
    }
    private function enviarInvitacionAProyecto($invitacion, $p,$ccEscuela,$ccColaboradores){
    //FIXME cachear y usar clonar()
    	$correoCoordinador = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO);
    
    	$correoEscuela = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_ESCUELA);
    	$correoColaborador = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_COLABORADORES);
    	
    	$context=array(Plantilla::_INVITACION => $invitacion);
		
		if ($ccEscuela){
			$correoEscuela->setProyecto($p);	
			$this->mailer->enviarCorreoAEscuela($correoEscuela, $context);
		}
		if ($ccColaboradores){
			$correoColaborador->setProyecto($p);	
			$this->mailer->enviarCorreoAColaboradores($correoColaborador, $context);
		}
		
		$context[Plantilla::_URL]=$this->mailer->resolveUrlParameter('abrir_invitacion', array('id'=>$invitacion->getId(), 'accion'=>'aceptar'));
		$correoCoordinador->setProyecto($p);
		$this->mailer->enviarCorreoACoordinador($correoCoordinador, $context);
			 	
    }

	public function invitarProyecto($instancia, $p,$ccEscuela,$ccColaboradores){
		$invitacion = $this->getInvitacion($instancia, $p);
			 if (empty($invitacion)){
			    $this->logger->info("Se invita al proyecto '".$p->getId()."' al evento '".$instancia->getTitulo()."'");
		
				$invitacion = new Invitacion();
				$invitacion->setInstanciaEvento($instancia);
				$invitacion->setProyecto($p);
				
				$em = $this->doctrine->getEntityManager();
		        $em->persist($invitacion);
		        $em->flush();
				//FIXME si falla el envio de mail por na invitacion, cuando se reenvia?
			 	$this->enviarInvitacionAProyecto($invitacion, $p,$ccEscuela,$ccColaboradores);
			 	
			 }else{
				$this->logger->trace("Ya exise una invitacion para el proyecto '".$proyecto->getId()."' al evento '".$instancia->getTitulo()."', no se hace nada.");
			}
        return $invitacion;
	}
	
	public function reinvitarProyecto($instancia, $p,$ccEscuela,$ccColaboradores) { 
		$invitacion = $this->getInvitacion($instancia, $p);
		if (empty($invitacion)) { 
			return false;
		} else { 
			$this->enviarInvitacionAProyecto($invitacion, $p,$ccEscuela,$ccColaboradores);
		}
		return $invitacion;
	}
	public function getReporteInvitaciones(InstanciaEvento $instancia){
		
		$res = $this->doctrine->getRepository('CpmJovenesBundle:Invitacion')->getCantidadesPorInstancia($instancia);
		
		return $res;
	}
	
}
