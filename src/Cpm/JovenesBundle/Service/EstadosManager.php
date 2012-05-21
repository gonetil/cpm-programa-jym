<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\EstadoProyecto;


define("ESTADO_INICIADO",0); //proyecto creado, sin archivo subido
define("ESTADO_ANULADO",1); //NO SIGUE
define("ESTADO_PRESENTADO",10); //archivo subido, proyecto aun no evaluado
define("ESTADO_APROBADO",20);
define("ESTADO_APROBADO_CLINICA",21);
define("ESTADO_DESAPROBADO",22);
define("ESTADO_REHACER",23);
define("ESTADO_FINALIZADO",30);


/**
 */
class EstadosManager
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
	    
	
    }
    
    public function getPreferredChoices() { 
    	return array(
				ESTADO_APROBADO, ESTADO_REHACER, ESTADO_DESAPROBADO, ESTADO_APROBADO_CLINICA
				); 
     }
    public function getSelectableEstados() { 
    	return array(
					ESTADO_PRESENTADO 	=> "Presentado (subir archivo)",
					ESTADO_APROBADO		=> "Aprobado",
					ESTADO_APROBADO_CLINICA => "Aprobado C",
					ESTADO_DESAPROBADO		=> "Desaprobado",
					ESTADO_REHACER => "Rehacer",
					ESTADO_ANULADO		=> "Anulado"
					//ESTADO_FINALIZADO => "Completado",
    	);
    }
    
    public function validarEstado($estado) {
    	switch ( $estado->getEstado() ) {
			case ESTADO_PRESENTADO:
				if ($estado->getArchivo() == null) 
					return "Para cambiar a estado presentado es necesario adjuntar el archivo con la presentación";
				break;
			default:
				break;
		}
		return "success";	
    }
    
    public function cambiarEstadoAProyecto($proyecto,$estado_nuevo) {
    	if (($resultado = $this->validarEstado($estado_nuevo)) == "success") {
    		$proyecto->setEstadoActual($estado_nuevo);
    		$estado_nuevo->setProyecto($proyecto);
    		$em = $this->doctrine->getEntityManager();
		    $em->persist($proyecto);
		    $em->persist($estado_nuevo);
		    $em->flush();
		    
		    $this->informarCambioDeEstado($proyecto);
    	}
    	return $resultado; 
    }
    //TODO completar esta funcion
    public function informarCambioDeEstado($proyecto) { 
    	$template = false;
    	switch ( $proyecto->getEstadoActual() ) {
			case ESTADO_APROBADO:
			case ESTADO_APROBADO_CLINICA:
				$template = "proyecto_aprobado";	
				break;
			case ESTADO_DESAPROBADO: 
				$template = "proyecto_desaprobado";	
				break;
			case ESTADO_REHACER:
				$template = "proyecto_rehacer";	
				break;
			default: //no se envian emails en otros casos
				break;
		}
		
    }
    
    /**
     * vuelve hacia atrás el estado del proyecto
     */
    public function deshacerEstadoDeProyecto($proyecto) {
    	$em = $this->doctrine->getEntityManager();
    	$estados = $em->getRepository('CpmJovenesBundle:EstadoProyecto')->getEstadosAnteriores($proyecto);
    	$estadoActual = $proyecto->getEstadoActual();
    	if (count($estados) == 0) { 
    		$this->logger->info("Se ignora el deshacer estado del proyecto {$proyecto->getId()} : no hay estados");
    		return false;
    	}

		$em->remove($estadoActual);  
		$nuevoEstado = (count($estados) > 1) ? $estados[1] : null;     	//FIXME aqui va null o se crea un estado "iniciado" ? 		
 		$proyecto->setEstadoActual($nuevoEstado);		
		$em->persist($nuevoEstado);
		$em->persist($proyecto);

	    $em->flush();
		
    }
    
    /**
     * retorna el nombre del archivo del proyecto, si existe
     * Para ello, busca en el estado actual del proyecto (si es presentado) o en el historial de esados el ultimo presentado
     */
     public function getArchivoPresentacion($proyecto) {
     	$estado = $proyecto->getEstadoActual();
     	if ($estado->getEstado() == ESTADO_PRESENTADO)
     		return $estado->getArchivo();
     	else { 
     		$em = $this->doctrine->getEntityManager();
     		$estados_anteriores = $em->getRepository('CpmJovenesBundle:EstadoProyecto')->getEstadosAnteriores($proyecto);
     		foreach ( $estados_anteriores as $estado_anterior) {
       			if ($estado_anterior->getEstado() == ESTADO_PRESENTADO)
       				return $estado_anterior->getArchivo();
			}
     	}
     	return "";	
     }
}    
	