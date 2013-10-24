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
use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Entity\AuditorioDia;
use Cpm\JovenesBundle\Entity\Presentacion;
use Cpm\JovenesBundle\Entity\PresentacionExterna;
use Cpm\JovenesBundle\Entity\PresentacionInterna;


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

    
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// TANDAS ///////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
     * 	Obtiene toda una tanda en json
     *
     * @Route("/tanda/{tanda_id}", name="get_tanda")
     * @Method("get")
     */
	public function mostrarTandaAction($tanda_id) {
		try { 
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
			$tandaArray = $tanda->toArray(20);
			$tandaArray['presentaciones_libres']=array();
			foreach($tanda->getPresentaciones() as $p){
				if (!$p->hasBloque())
					$tandaArray['presentaciones_libres'][]=$p->toArray(2);
			}		
			return $this->createJsonResponse($tandaArray);
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}

	/**
     * 	Recibe un array JSON presentacionId y bloqueID
     *
     * @Route("/tanda/{tanda_id}/savePresentaciones")
     * @Method("post")
    */
	public function guardarTandaAction($tanda_id) {
		
		try { 
			$tanda  = $this->getEntityForUpdate('CpmJovenesBundle:Tanda', $tanda_id);
			$vars = $this->getVarsFromJSON();
			
			if (empty($vars['presentaciones']) || !is_array($vars['presentaciones']))
				throw new \InvalidArgumentException("presentaciones no es un array, en cambio tiene el siguiente contenido ".var_export($vars['presentaciones'], true)); 
			
			$numCambios = $this->getChapaManager()->guardarRedistribucionDeTanda($tanda,$vars['presentaciones']);
			return $this->answerOk("Se guardo la tanda, y se efectivizaron $numCambios movimientos de presentaciones");
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     *
     * @Route("/tanda/{tanda_id}/resetPresentaciones")
     * @Method("post")
    */
	public function resetearTandaAction($tanda_id) {
		
		try { 
			$tanda  = $this->getEntityForUpdate('CpmJovenesBundle:Tanda', $tanda_id);
			$numCambios = $this->getChapaManager()->resetearPresentacionesDeTanda($tanda);
			return $this->answerOk("Se reinicializó la tanda ($tanda_id), y se liberaron $numCambios presentaciones");
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}


	/**
     * 	Lista las tandas del ciclo activo
     *
     * @Route("/tanda/")
     * @Method("get")
     */
	public function listarTandasAction() 
	{
		try { 
			$em = $this->getDoctrine()->getEntityManager();
		 
			$ciclo = $this->getJYM()->getCicloActivo();
	        $tandas = $em->getRepository('CpmJovenesBundle:Tanda')->findAllQuery($ciclo)->getResult();
    		return $this->newJsonResponse($tandas,1);			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// DIA ///////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
     * Duplica la estructura de $dia_origen (auditoriosDia y sus bloques) en $dia_destino . 
     * Asigna $dia_destino a la misma tanda de $dia_origina
     * Si $dia_destino no existe se crea
     *
     * @Route("/dia/{dia_origen_id}/duplicar")
     * @Method("post")
     */	
	public function duplicarDiaAction($dia_origen_id) {
		$em = $this->getDoctrine()->getEntityManager();
	    	try {
			//$vars = $this->getVarsFromJSON();
			$args= $this->getRequest()->query;
			
			if (empty($dia_origen_id))
				throw new \InvalidArgumentException("Falta el id de dia_origen");
			$dia_origen = $this->getEntity('CpmJovenesBundle:Dia', $dia_origen_id);
			
			$dia_destino_id=$args->get('dia_destino');
	    	if (!empty($dia_destino_id)){
		   		$dia_destino = $this->getEntityForUpdate('CpmJovenesBundle:Dia', $dia_destino_id);
				$tandaDestino = $dia_destino->getTanda();
			}else{
		   		$dia_destino = null;
		   		$tandaDestino = $dia_origen->getTanda();
		    }
    		$dia_destino = $this->getChapaManager()->clonarDia($dia_origen,$dia_destino);
		    $tandaDestino->addDia($dia_destino);
		    		
   			$em->persist($tandaDestino);
    		$em->flush();
    		return $this->newJsonResponse($dia_destino, 3);
    	} catch (\Exception $e) {
    	   	return $this->answerError($e);
    	}
	}
	/**
     * @Route("/dia") 
     * @Method("post")
     */
	public function crearDiaAction() {
		try { 
			$vars = $this->getVarsFromJSON();
			if (empty($vars['tanda']))
				throw new \InvalidArgumentException("Falta el id de tanda");
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $vars['tanda']);
			$dia = new Dia(-1);
			$tanda->addDia($dia);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($tanda);
	        $em->flush();
			return $this->newJsonResponse($dia, 2);
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     * @Route("/dia/{diaId}")
     * @Method("DELETE")
     */
	public function eliminarDiaAction($diaId) {
		try { 
			$dia = $this->getEntityForDelete('CpmJovenesBundle:Dia', $diaId);
			$tanda=$dia->getTanda();
			$em = $this->getDoctrine()->getEntityManager();
			$tanda->removeDia($dia);
			$em->remove($dia);
	        $em->persist($tanda);
	        $em->flush();
			return $this->answerOk("Se eliminó con éxito el dia $diaId");			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// AUDITORIO DIA /////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
     * @Route("/auditorioDia/{id}")
     * @Method("get")
     */
     public function mostrarAuditorioDiaAction($id) {
     	try {
	     	$ad  = $this->getEntity('CpmJovenesBundle:AuditorioDia', $id);
			return $this->newJsonResponse($ad,2);			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
     }
     
	/**
     * @Route("/auditorioDia") 
     * @Method("post")
     */
	public function crearAuditorioDiaAction() {
		return $this->forward("CpmJovenesBundle:Cronograma:modificarAuditorioDia", array('id'=>-1));
	}

	/**
     *
     * @Route("/auditorioDia/{id}")
     * @Method("post")
     */
	public function modificarAuditorioDiaAction($id) {
		try { 
			if ($id == -1)
				$ad  = new AuditorioDia();
			else
				$ad = $this->getEntityForUpdate('CpmJovenesBundle:AuditorioDia', $id);
	    
	        $args = $this->getVarsFromJSON();
	       	if (!empty($args['auditorio']) && !empty($args['auditorio']['id']))
	       		$ad->setAuditorio( $this->getEntity('CpmJovenesBundle:Auditorio', $args['auditorio']['id']));
	
	       	if (!empty($args['dia']))
	       		$ad->setDia( $this->getEntity('CpmJovenesBundle:Dia', $args['dia']));

 			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($ad);
	        $em->flush();
	        return $this->newJsonResponse($ad, 2);		
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     * @Route("/auditorioDia/{auditorioDiaId}")
     * @Method("DELETE")
     */
	public function eliminarAuditorioDiaAction($auditorioDiaId) {
		try { 
			$ad = $this->getEntityForDelete('CpmJovenesBundle:AuditorioDia', $auditorioDiaId);
			$dia=$ad->getDia();
			$dia->removeAuditorioDia($ad);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($ad);
	        $em->persist($dia);
	        $em->flush();
			return $this->answerOk("Se eliminó con éxito el Auditorio del dia número {$dia->getNumero()}");			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// BLOQUES ///////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
     * Gets a Bloque entity
     * @Route("/bloque/{id}", name="mostrar_bloque")
     * @Method("get")
     */
     public function mostrarBloqueAction($id) {
     	try {
	     	$bloque  = $this->getEntity('CpmJovenesBundle:Bloque', $id);
			return $this->newJsonResponse($bloque);			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
     }
     
	/**
     * Creates a new Bloque entity.
     *
     * @Route("/bloque")
     * @Method("post")
     */
	public function crearBloqueAction() {
		return $this->forward("CpmJovenesBundle:Cronograma:modificarBloque", array('id'=>-1));
	}
	
	/**
     * Modifies a Bloque entity.
     *
     * @Route("/bloque/{id}")
     * @Method("post")
     */
	public function modificarBloqueAction($id) {
		
		try { 
			if ($id == -1)
				$bloque  = new Bloque();
			else
				$bloque  = $this->getEntityForUpdate('CpmJovenesBundle:Bloque', $id);
	   		 $em = $this->getDoctrine()->getEntityManager();
			
	        $args = $this->getVarsFromJSON();
	        $auditorioDia = $bloque->getAuditorioDia();
	       	if (!empty($args['auditorioDia'])){
	       		$newAD = $this->getEntity('CpmJovenesBundle:AuditorioDia', $args['auditorioDia'] );
	       		if ($auditorioDia ==null || !$auditorioDia->equals($newAD)){
		       		$auditorioDia=$newAD;
		       		$auditorioDia->addBloque($bloque);
		       		
		       	}
	       	}
	       	
	       	if (!empty($args['horaInicio']))
				$bloque->setHoraInicio( date_create_from_format ('H:i', $args['horaInicio']));
				
	        if (!empty($args['duracion']))
				$bloque->setDuracion( (int) $args['duracion'] );

	        if (isset($args['nombre']))
				$bloque->setNombre( (string) $args['nombre'] );

	        if (isset($args['tienePresentaciones']))
				$bloque->setTienePresentaciones((bool) $args['tienePresentaciones'] );

	        if (isset($args['ejesTematicos'])){
	        	$ejesTematicos=array();
	        	foreach($args['ejesTematicos'] as $tema)
		        	$ejesTematicos[]=$this->getEntity('CpmJovenesBundle:Tema', (int)$tema['id'] );
		        $bloque->setEjesTematicos( $ejesTematicos );
	        }
	        
	        if (isset($args['areasReferencia'])){
	        	$areasReferencia=array();
	        	foreach($args['areasReferencia'] as $eje)
		        	$areasReferencia[]=$this->getEntity('CpmJovenesBundle:Eje', (int)$eje['id'] );
		        	
		        $bloque->getAreasReferencia()->clear();
	        	$em->flush();
	        	$bloque->setAreasReferencia( $areasReferencia );
	        }

	        if ($auditorioDia){
				foreach($auditorioDia->getBloques() as $bi){
					$em->persist($bi);
				}
			
		        $em->persist($auditorioDia);
	        }else{
			$em->persist($bloque);
	        	
	        }
 	        $em->flush();
	        return $this->newJsonResponse($bloque);		
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
     
  	/**
     * @Route("/bloque/{bloqueId}")
     * @Method("DELETE")
     */
	public function eliminarBloqueAction($bloqueId) {
		try { 
			$bloque = $this->getEntityForDelete('CpmJovenesBundle:Bloque', $bloqueId);
			$ad=$bloque->getAuditorioDia();
			$ad->removeBloque($bloque);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($bloque);
	        $em->persist($ad);
	        $em->flush();
			return $this->answerOk("Se eliminó con éxito el Bloque del dia número {$ad->getDia()->getNumero()} del Auditorio {$ad->getAuditorio()->getNombre()}");			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
     
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// Listados de referencia ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
     * @Route("/auditorio")
     * @Method("get")
     */
	public function listarAuditoriosAction() {
		try { 
			$auditorios = $this->getEntityManager()->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado' => false));
			return $this->newJsonResponse($auditorios);				
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
			
	}
	
	/**
	 * 	Obtiene una lista con todos los tipos de presentaciones
     *
     * @Route("/tipoPresentacion", name="get_tipos_presentaciones")
     * @Method("get")
     */
	public function listarTipoPresentacionAction() {
		
		try { 
			$producciones = $this->getEntityManager()->getRepository('CpmJovenesBundle:Produccion')->findBy(array('anulado' => false));
			return $this->newJsonResponse($producciones,2);			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     * @Route("/areaReferencia")
     * @Method("get")
     */
	public function listarAreasReferenciaAction() {
		
		try { 
			$areas= $this->getEntityManager()->getRepository('CpmJovenesBundle:Eje')->findBy(array('anulado' => false));
			return $this->newJsonResponse($areas,2);			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
	}

	/**
     * @Route("/ejeTematico")
     * @Method("get")
     */
	public function listarEjesTematicosAction() {
		
		try { 
			$temas= $this->getEntityManager()->getRepository('CpmJovenesBundle:Tema')->findBy(array('anulado' => false));
			return $this->newJsonResponse($temas,2);			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
	}

	/**
     * @Route("/produccion")
     * @Method("get")
     */
	public function listarProduccionesAction() {
		
		try { 
			$producciones= $this->getEntityManager()->getRepository('CpmJovenesBundle:Produccion')->findBy(array('anulado' => false));
			return $this->newJsonResponse($producciones,2);			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
	}

	/**
     * @Route("/provincia")
     * @Method("get")
     */
	public function listarProvinciasAction() {
		
		try { 
			$provincias= PresentacionExterna::provincias();
			for($i=0; $i<count($provincias);$i++){
				$provincias[$i]=(object) array('nombre'=>$provincias[$i]);
			}
			return $this->newJsonResponse($provincias);			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// PRESENTACIONES ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
     * @Route("/presentacion/{id}")
     * @Method("get")
     */
     public function mostrarPresentacionAction($id) {
     	try {
	     	$p = $this->getEntity('CpmJovenesBundle:Presentacion', $id);
			return $this->newJsonResponse($p,1);			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
     }
	
	/**
     *
     * @Route("/presentacion")
     * @Method("post")
     */
	public function crearPresentacionAction() {
		return $this->forward("CpmJovenesBundle:Cronograma:modificarPresentacion", array('id'=>-1));
	}
     
	/**
     *
     * @Route("/presentacion/{id}", name="presentacion_edit_json")
     * @Method("post")
     */
	public function modificarPresentacionAction($id) {
		
		try { 
			if ($id == -1)
				$presentacion  = new PresentacionExterna();
			else
				$presentacion  = $this->getEntityForUpdate('CpmJovenesBundle:Presentacion', $id);
	   		 $em = $this->getDoctrine()->getEntityManager();
	
	        $args = $this->getVarsFromJSON();
	       	if (isset($args['bloque'])){
	       		if (!empty($args['bloque']))
	       			$newBloque=$this->getEntity('CpmJovenesBundle:Bloque', $args['bloque'] );
	       		else
		       		$newBloque=null;

	       		if ($presentacion->hasBloque())
	       			$oldBloque=$presentacion->getBloque();
	       		else
		       		$oldBloque=null;
				
				if ($oldBloque != null && !$oldBloque->equals($newBloque)){
					$oldBloque->removePresentacion($presentacion);
				}
				if ($newBloque != null && !$newBloque->equals($oldBloque)){
					$newBloque->addPresentacion($presentacion);
				}

				//$presentacion->setBloque( $newBloque);
	        	
	       	}
	       	if (!empty($args['tanda'])){
	        	$nuevaTanda = $this->getEntity('CpmJovenesBundle:Tanda', $args['tanda']);
	        	
    			if($presentacion->getTanda() == null)
    				$nuevaTanda->addPresentacion($presentacion);
    			elseif (!$nuevaTanda->equals($presentacion->getTanda()))
    				$this->getChapaManager()->cambiarPresentacionDeTanda($presentacion,$nuevaTanda);
	        }
	        if (isset($args['posicion']) && $presentacion->hasBloque()) { 
		        
		        $bloque = $presentacion->getBloque();
		        $bloque->reposicionarPresentacion($presentacion, (int) $args['posicion']);
		    	$em->persist($bloque);
			}

	        if($presentacion->esExterna()){
		       // $presentacion  = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionExterna', $id);
		        
		        if (!empty($args['titulo']))
					$presentacion->setTitulo((string)$args['titulo']);
				
				if (isset($args['ejeTematico'])){
		        	if (empty($args['ejeTematico']))
		        		$ejeTematico=null;
					else
						$ejeTematico=$this->getEntity('CpmJovenesBundle:Tema', (int)$args['ejeTematico'] );
			        $presentacion->setEjeTematico( $ejeTematico );
		        }
		        
		        if (isset($args['areaReferencia'])){
		        	if (empty($args['areaReferencia']))
		        		$areaReferencia=null;
					else
						$areaReferencia=$this->getEntity('CpmJovenesBundle:Eje', (int)$args['areaReferencia'] );
			        $presentacion->setAreaReferencia( $areaReferencia );
		        }
		        
		        if (isset($args['tipoPresentacion'])){
		        	if (empty($args['tipoPresentacion']))
		        		$tipoPresentacion=null;
					else
						$tipoPresentacion=$this->getEntity('CpmJovenesBundle:Produccion', (int)$args['tipoPresentacion'] );
			        $presentacion->setTipoPresentacion( $tipoPresentacion );
		        }
		        
		        if (isset($args['escuela']))
					$presentacion->setEscuela( (string) $args['escuela'] );
	
		        if (isset($args['provincia']))
					$presentacion->setProvincia( (string) $args['provincia'] );
	
		        if (isset($args['localidad']))
					$presentacion->setLocalidad( (string) $args['localidad'] );

		        if (isset($args['valoracion']))
					$presentacion->setValoracion( (string) $args['valoracion'] );
				
				if (isset($args['personasConfirmadas']))
					$presentacion->setPersonasConfirmadas((int) $args['personasConfirmadas'] );
					
				if (isset($args['apellidoCoordinador']))
					$presentacion->setApellidoCoordinador((string)$args['apellidoCoordinador'] );
				if (isset($args['nombreCoordinador']))
					$presentacion->setNombreCoordinador((string) $args['nombreCoordinador'] );
					
	    	    }
	    	    
	    	if (isset($args['destino'])) { //hay un reordenamiento
	        	
	        	$bloque = $this->getEntity('CpmJovenesBundle:Bloque',(int)$args['destino']['bloque']);
	        	$presentaciones = array();
	        	foreach ( $args['destino']['newOrder'] as $order) {
       				$p = $this->getEntity('CpmJovenesBundle:Presentacion',(int)$order['presentacion']);
       				$p->setPosicion($order['posicion']);
       				$presentaciones[] = $p;  
       				$em->persist($p);
				}
				$bloque->setPresentaciones($presentaciones);
				$em->persist($bloque);
	        }

	    	if (isset($args['origen'])) { //hay un reordenamiento
	        	
	        	$bloque = $this->getEntity('CpmJovenesBundle:Bloque',(int)$args['origen']['bloque']);
	        	$presentaciones = array();
	        	foreach ( $args['origen']['newOrder'] as $order) {
       				$p = $this->getEntity('CpmJovenesBundle:Presentacion',(int)$order['presentacion']);
       				$p->setPosicion($order['posicion']);
       				$presentaciones[] = $p;  
       				$em->persist($p);
       				
				}
				$bloque->setPresentaciones($presentaciones);
	        }
	        	            
			$em->persist($presentacion);
	        $em->flush();
	        
	        
 			
	        return $this->newJsonResponse($presentacion);		
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
     
  	/**
     * @Route("/presentacion/{id}")
     * @Method("DELETE")
     */
	public function eliminarPresentacionAction($id) {
		try { 
			$em = $this->getDoctrine()->getEntityManager();
			$presentacion = $this->getEntityForDelete('CpmJovenesBundle:Presentacion', $id);
			
			if ($presentacion->getTipo() == 'interna') { //pongo la invitacion como rechazada
			
				$invitacion = $presentacion->getInvitacion();
				$invitacion->setAceptoInvitacion(false);
				$em->persist($invitacion);
			}
			
			$em->remove($presentacion);
	        $em->flush();
			return $this->answerOk("Se eliminó con éxito la presentación externa número {$id}");			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// INTERNOS ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * Agrego estos dos helpers answerOk y answerError para concentrar los tipos de respuesta, por si cambiamos de JSON a http_code y vice versa
	 */
	private function answerOk($message="OK") {
		return $this->createJsonResponse(array('status' => 'success', 'message' => $message));
	}
	
	private function answerError($exception) {
		if (!($exception instanceof \Exception)){
			$exception = new \InvalidArgumentException("exception debe ser una Exception, no un string. Vino :".$exception);
		}
		
		$message=get_class($exception).':'.$exception->getMessage();
		$this->get('logger')->warn("Se produjo una exception en CronogramaController: $message. \n Trace: ".$exception->getTraceAsString());
		
		$response = new Response(json_encode($message), 500);
    	$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	private function getVarsFromJSON() {
		//en teoría el request tiene content-type application/json
		$content = $this->getRequest()->getContent();
	
		$content = str_ireplace('"\\',"'",$content);
		$content = str_ireplace('\"',"'",$content);
		$content = stripslashes($content);
		
		if (!empty($content))
	    {
	    	$params = json_decode($content,true); // 2nd param to get as array
	        
	         switch(json_last_error()) {
		        case JSON_ERROR_NONE:
		            //echo ' - Sin errores';
		        break;
		        case JSON_ERROR_DEPTH:
		            echo ' - Excedido tamaño máximo de la pila';
		        break;
		        case JSON_ERROR_STATE_MISMATCH:
		            echo ' - Desbordamiento de buffer o los modos no coinciden';
		        break;
		        case JSON_ERROR_CTRL_CHAR:
		            echo ' - Encontrado carácter de control no esperado';
		        break;
		        case JSON_ERROR_SYNTAX:
		            echo ' - Error de sintaxis, JSON mal formado';
		        break;
		        case JSON_ERROR_UTF8:
		            echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
		        break;
		        default:
		            echo ' - Error desconocido';
		        break;
    		}
	    }else
	    	return array();
	   // var_dump($params);
	    return $params;
	}


	protected function entityToArray($entity, $depth){
		if($depth == 0)
			return;
		if (!is_object($entity))
			return $entity;
		
		if (method_exists($entity, 'toArray')){
			$resultArray=$entity->toArray($depth);
		}else{
			
			$reflectionEntity = new \ReflectionObject($entity);
			//TODO muy lindo pero no soporta ciclos ...
			$resultArray=array();
		    foreach ($reflectionEntity->getProperties(\ReflectionProperty::IS_PUBLIC + \ReflectionProperty::IS_PROTECTED) as $prop) {
				$pv = $prop->getValue($entity);
			    if ($pv == null)
			      	$jValue="";
			    elseif (is_object($pv) && ($pv instanceof \Traversable)){
					$jValue= array();
					foreach ( $pv as $pvi) {
			       		$jValue[] = $this->entityToArray($pvi, $depth-1);
					}
				}elseif (is_object($pv)){
			      	$jValue=$this->entityToArray($pv, $depth-1);
			    }else{
				  	$jValue=$pv;
			    }  
			    $resultArray[$prop->getName()]=$jValue;
		    }
		}
		return $resultArray;
	}
	protected function newJsonResponse($something, $depth=2){
		if(!is_object($something) && !is_array($something))
			$resultArray=$something;
		elseif (is_array($something) || ($something instanceof \Traversable)){
			$resultArray = array();
			foreach ( $something as $entity) {
	       			$resultArray[] = $this->entityToArray($entity, $depth);
			}
		}else{
			$resultArray=$this->entityToArray($something, $depth);
		}
		return $this->createJsonResponse($resultArray);			
	}
}