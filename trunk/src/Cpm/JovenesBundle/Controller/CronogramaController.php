<?php
/*
 * Created on 17/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque controller.
 *
 * @Route("/cronograma")
 */
class CronogramaController extends BaseController
{
	
	/**
	 * Agrego estos dos helpers answerOk y answerError para concentrar los tipos de respuesta, por si cambiamos de JSON a http_code y vice versa
	 */
	private function answerOk($message="") {
		//return $this->createJsonResponse(array('status' => 'success', 'message' => $message);
		$response = $this->createSimpleResponse(200,$message);
		return $response->send();		
	}
	
	private function answerError($message="") {
		//return $this->createJsonResponse(array('status' => 'error', 'message' => $message);
		$response = $this->createSimpleResponse(500,$message);
		return $response->send();
	}
	
	private function populateBloqueFromRequest($bloque,$request) {
		
		$bloque->setPosicion( $request->get('posicion') );
		$bloque->setHoraInicio( $request->get('horaInicio') );
		$bloque->setDuracion( $request->get('duracion') );
		$bloque->setAuditorioDia( $request->get('auditorioDia') );
		$bloque->setNombre( $request->get('nombre') );
		$bloque->setTienePresentaciones( $request->get('tienePresentaciones') );
		
		$p = $request->get('presentaciones');
		if ( !empty($p))
			$bloque->setPresentaciones ( $p ) ;
		return $bloque;
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
		$bloque = $this->populateBloqueFromRequest($bloque,$request);
		
		try { 
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bloque);
	        $em->flush();
	        $this->answerOk($bloque->getId());
		} catch (\Exception $e) {
			$this->answerError($e);
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
        $bloque = $this->populateBloqueFromRequest($bloque,$request);
        try { 
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bloque);
	        $em->flush();
	        $this->answerOk($bloque->getId());
		} catch (\Exception $e) {
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
		} catch (\Exception $e) {
			$this->answerError($e);
		}
		
		try {
			return $this->createJsonResponse($tanda->toArray(5,false));			
		}
		catch (\Exception $e) {
			$this->answerError($e);
		}
		
	}	
	
}