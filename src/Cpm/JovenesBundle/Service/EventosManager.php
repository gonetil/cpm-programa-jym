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
    	
    	$plantillaCoordinador = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO);
    	$plantillaEscuela = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_ESCUELA);
    	$plantillaColaborador = $this->mailer->getCorreoFromPlantilla(Plantilla::INVITACION_A_EVENTO_A_COLABORADORES);
    	
        foreach ( $invitacionBatch->getProyectos() as $p ) {
			 $invitacion = $this->getInvitacion($instancia, $p);
			 if (empty($invitacion)){
			    $this->logger->info("Se invita al proyecto '".$p->getId()."' al evento '".$instancia->getTitulo()."'");
		
			 	$invitacion = $this->invitarProyecto($instancia, $p);
			 	
			 	$context=array(Plantilla::_INVITACION => $invitacion);
			 	
			 	if ($ccEscuela){
			 		$plantillaEscuela->setProyecto($p);	
				 	$this->mailer->enviarCorreoAEscuela($admin, $plantillaEscuela, $context);
			 	}
				if ($ccColaboradores){
			 		$plantillaColaborador->setProyecto($p);	
				 	$this->mailer->enviarCorreoAColaboradores($admin, $plantillaColaborador, $context);
				}
				$context[Plantilla::_URL]=$this->mailer->resolveUrl('', array('id'=>$invitacion->getId(), 'accion'=>'aceptar'));
				$plantillaCoordinador->setProyecto($p);
				 	
				$this->mailer->enviarCorreoACoordinador($admin, $plantillaCoordinador, $context);
			 	
			 }else{
				$this->logger->trace("Ya exise una invitacion para el proyecto '".$proyecto->getId()."' al evento '".$instancia->getTitulo()."', no se hace nada.");
			}
		}
    }

	public function invitarProyecto($instancia, $p){
		$invitacion = new Invitacion();
		$invitacion->setInstanciaEvento($instancia);
		$invitacion->setProyecto($p);
		
		$em = $this->doctrine->getEntityManager();
        $em->persist($invitacion);
        $em->flush();
        return $invitacion;
	}
}
