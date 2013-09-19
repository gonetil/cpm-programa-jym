<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;


use Cpm\JovenesBundle\Entity\Bloque;
use Cpm\JovenesBundle\Entity\Tanda;
use Cpm\JovenesBundle\Entity\Auditorio;

/**
 * Bloque controller.
 *
 * @Route("/cronograma")
 */
class CronogramaController extends BaseController
{
	
	/**
     * 
     * @Route("/", name="index_cronograma")
     * @Template("CpmJovenesBundle:Cronograma:index.html.twig")
     * @Method("get")
     */
	public function indexAction()
    {
        return array();
	}
	/**
     * Creates a new Bloque entity.
     *
     * @Route("/bloque", name="crear_bloque")
     * @Method("post")
     */
	public function crearBloqueAction() {
		$bloque  = new Bloque();
        $request = $this->getRequest();
		$this->populateBloqueFromRequest($bloque,$request);
		try { 
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bloque);
	        $em->flush();
	        return $this->answerOk($bloque->getId());
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     * Modifies a Bloque entity.
     *
     * @Route("/bloque/{id}", name="modificar_bloque")
     * @Method("post")
     */
	public function modificarBloqueAction($id) {
		$bloque  = $this->getEntityForUpdate('CpmJovenesBundle:Bloque', $id);
        $request = $this->getRequest();
        $this->populateBloqueFromRequest($bloque,$request);
        try { 
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bloque);
	        $em->flush();
	        return $this->createJsonResponse($bloque->toArray(2,false));		
	        //return $this->answerOk($bloque->getId()); //Ariel me pidio JSON
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}	
	

    /**
     * Removes a Bloque entity
     * @Route("/bloque/{id}", name="borrar_bloque")
     * @Method("delete")
     */
     public function borrarBloqueAction($id) {
     	$chapaManager = $this->getChapaManager();
     	
     	try {
	     	$bloque  = $this->getEntity('CpmJovenesBundle:Bloque', $id);
	     	$chapaManager->borrarBloque($bloque);
			return $this->answerOk("Bloque eliminado satisfactoriamente");			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
     }

    /**
     * Gets a Bloque entity
     * @Route("/bloque/{id}", name="mostrar_bloque")
     * @Method("get")
     */
     public function mostrarBloqueAction($id) {
     	try {
	     	$bloque  = $this->getEntity('CpmJovenesBundle:Bloque', $id);
			return $this->createJsonResponse($bloque->toArray(2,false));			
		}
		catch (\Exception $e) {
			$this->answerError($e);
		}
     }


	/**
     * 	Lista los bloques desde un AuditorioDia
     *
     * @Route("/bloques/{auditodiodia_id}", name="listar_bloques")
     * @Method("get")
     */
	public function listarBloquesAction($auditodiodia_id) {
		
		try { 
			$auditorioDia  = $this->getEntity('CpmJovenesBundle:AuditorioDia', $auditodiodia_id);
		} catch (\Exception $e) {
			$this->answerError($e);
		}
		
		try {
			return $this->createJsonResponse($auditorioDia->toArray(2,false));			
		}
		catch (\Exception $e) {
			$this->answerError($e);
		}
		
	}
	
	/**
     * 	Obtiene toda una tanda en json
     *
     * @Route("/tanda/{tanda_id}", name="get_tanda")
     * @Method("get")
     */
	public function getTandaJSONAction($tanda_id) {
		try { 
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
			return $this->createJsonResponse($tanda->toArray(5,false));
		} catch (\Exception $e) {
			$this->answerError($e);
		}
	}	

	/**
     * 	Obtiene una lista con todos los tipos de presentaciones
     *
     * @Route("/tipo_presentacion", name="get_tipos_presentaciones")
     * @Method("get")
     */
	public function getTipoPresentacionAction() {

			$producciones = $this->getEntityManager()->getRepository('CpmJovenesBundle:Produccion')->findBy(array('anulado' => false));
			$array = array();
			foreach ( $producciones as $prod) {
       			$array[] = array('id' => $prod->getId(),
       							 'nombre' => $prod->getNombre(),
       							 'slug' => $prod->getTipoPresentacion(),
       							 'duracion' => $prod->getDuracionEstimada() );
			}
			return $this->createJsonResponse($array);		
	}
	
	
	/**
     * Cambia la presentacion $presentacion_id a la tanda $tanda_id
     *
     * @Route("/cambiarDeTanda", name="cambiar_de_tanda")
     * @Method("post")
     */
	public function cambiarPresentacionDeTandaAction() {
     	$presentacion_id = $this->getRequest()->get('presentacion_id');
     	$tanda_id = $this->getRequest()->get('tanda_id');

    	try { 
    		$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
    		$presentacion = $this->getEntity('CpmJovenesBundle:Presentacion',$presentacion_id);
    		$chapaManager = $this->getChapaManager();
    		$msg = $chapaManager->cambiarDeTanda($presentacion,$tanda);
    		return $this->answerOk($msg);
    	} catch (\Exception $e) {
    		    return $this->answerError($e);		
    	}

	}
	
	
	
	/**
     * Duplica la estructura de $dia_origen (auditoriosDia y sus bloques) en $dia_destino . 
     * Asigna $dia_destino a la misma tanda de $dia_origina
     * Si $dia_destino no existe se crea
     *
     * @Route("/duplicarDia", name="duplicar_dia")
     * @Method("post")
     */	
	public function duplicarDiaAction() {
		$dia_origen_id = $this->getRequest()->get('dia_origen');
		$dia_destino_id = $this->getRequest()->get('dia_destino');
		
		try {
			$dia_origen = $this->getEntity('CpmJovenesBundle:Dia', $dia_origen_id);
		}
		catch (\Exception $e) {
    		    return $this->answerError($e); //dia origen tiene que existir si o si		
    	}
    	
    	
    	$chapaManager = $this->getChapaManager();
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$em->getConnection()->beginTransaction();
    	try { 
	    		$dia_destino = null;
	    		try {
					$dia_destino = $this->getEntity('CpmJovenesBundle:Dia', $dia_destino_id);
					$chapaManager->vaciarDia($dia_destino);
				}
				catch (\Exception $e) {
		    		$dia_destino = new Dia();	//si el dia no existia, simplemente lo creo	
		    	}
		    	$dia_destino = $chapaManager->clonarDia($dia_origen,$dia_origen->getTanda());
    			$em->persist($dia_destino);
    			$em->flush();
		    	return $this->createJsonResponse($dia_destino->toArray(3,false));
    	} catch (\Exception $e) {
    	   	$em->getConnection()->rollback();
			$em->close();
            return $this->answerError($e);
    	}
    	
	}
	
	
	
	
	/**
	 * Agrego estos dos helpers answerOk y answerError para concentrar los tipos de respuesta, por si cambiamos de JSON a http_code y vice versa
	 */
	private function answerOk($message="") {
		return $this->createJsonResponse(array('status' => 'success', 'message' => $message));
		//$response = $this->createSimpleResponse(200,$message);
		return $response->send();		
	}
	
	private function answerError($message="") {
		return $this->createJsonResponse(array('status' => 'error 500', 'message' => $message));
		//$response = $this->createSimpleResponse(500,$message);
		return $response->send();
	}
	
	private function populateBloqueFromRequest($bloque,$request) {
		$request = $this->getVarsFromJSON($request);
		
		$auditorioDia = $this->getEntity('CpmJovenesBundle:AuditorioDia', $this->getVar($request,'auditorioDia') );

		$horaInicio = date_create_from_format ('h:i', $this->getVar($request,'horaInicio') );
		$bloque->setHoraInicio( $horaInicio );
		
		$bloque->setPosicion( $this->getVar($request,'posicion') );
		$bloque->setDuracion( $this->getVar($request,'duracion') );
		$bloque->setAuditorioDia( $auditorioDia );
		$bloque->setNombre( $this->getVar($request,'nombre') );
		$bloque->setTienePresentaciones( $this->getVar($request,'tienePresentaciones') );
		
		$p = $this->getVar($request,'presentaciones');
		if ( !empty($p))
			$bloque->setPresentaciones ( $p ) ;
			
		return $bloque;
	}
	
	private function getVar($array,$key) {
		return ( array_key_exists($key,$array) ? $array[$key] : '' );
	}
	
	private function getVarsFromJSON($request) {
		$content = $request->getContent();
		if (!empty($content))
	    {
	        $params = json_decode($content,true); // 2nd param to get as array
	    }
	    return $params;
	}

}