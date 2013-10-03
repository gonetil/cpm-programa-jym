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
	public function getTandaAction($tanda_id) {
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
     * 	Recibe un super JSON con toda una tanda y lo almacena en la BD
     *
     * @Route("/tanda/{tanda_id}", name="guardar_tanda")
     * @Method("post")
    */
	public function guardarTandaAction($tanda_id) {
		
		try { 
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
		
			$vars = $this->getVarsFromJSON();
			$tanda_json = $vars['tanda'];
			
			$tanda = json_decode($tanda_json,true);
			var_dump($tanda); die;
		} catch (\Exception $e) {
			return $this->answerError("Tanda $tanda_id no pudo encontrarse");
		}
		
	}


	/**
     * 	Lista las tandas
     *
     * @Route("/tanda/")
     * @Method("get")
     * TODO ver si no debería recibir un event_id o algo así que permita filtrar las tandas
     * 
     */
	public function listarTandasAction() 
	{
		try { 
			$em = $this->getDoctrine()->getEntityManager();
			//FIXME retornar solo tandas del ciclo activo 
	        $tandas = $em->getRepository('CpmJovenesBundle:Tanda')->findAll();
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
     * @Route("/duplicarDia", name="duplicar_dia")
     * @Method("post")
     */	
	public function duplicarDiaAction() {
		try {
			$vars = $this->getVarsFromJSON();

			$dia_origen_id = $vars['dia_origen'];
			$dia_origen = $this->getEntity('CpmJovenesBundle:Dia', $dia_origen_id);
				
	    	if (!empty($vars['dia_destino'])){
		   		$dia_destino = $this->getEntity('CpmJovenesBundle:Dia', $vars['dia_destino']);
				$tandaDestino = $dia_destino->getTanda();
			}else{
		   		$dia_destino = null;
		   		$tandaDestino = $dia_origen->getTanda();
		    }
		    $em = $this->getDoctrine()->getEntityManager();
	    	$em->getConnection()->beginTransaction();
    		$dia_destino = $this->getChapaManager()->clonarDia($dia_origen,$dia_destino);
		    if(!$tandaDestino->equals($dia_origen->getTanda()))
		    	$tandaDestino->addDia($dia_destino);
		    		
   			$em->persist($tandaDestino);
    		$em->flush();
    		$em->getConnection()->commit();
    			
    		return $this->newJsonResponse($dia_destino, 3);
    	} catch (\Exception $e) {
    	   	$em->getConnection()->rollback();
			$em->close();
            return $this->answerError($e);
    	}
	}
	/**
     * @Route("/dia") 
     * @Method("post")
     */
	public function crearDiaAction() {
		try { 
			$tandaId=$this->getRequest()->query->get('tandaId');
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tandaId);
			$dia = $tanda->addDia(new Dia(-1));
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($dia);	
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
			$ad = $this->getEntity('CpmJovenesBundle:AuditorioDia', $auditorioDiaId);
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
	       	if (!empty($args['auditorioDia']))
	        	$bloque->setAuditorioDia( $this->getEntity('CpmJovenesBundle:AuditorioDia', $args['auditorioDia'] ) );
	        if (!empty($args['horaInicio']))
				$bloque->setHoraInicio( date_create_from_format ('H:i', $args['horaInicio']));
			
			//FIXME corregir posicion del bloque
	        if (isset($args['posicion']))
				$bloque->setPosicion((int) $args['posicion'] );

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
	        
 			$em->persist($bloque);
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
			$bloque = $this->getEntity('CpmJovenesBundle:Bloque', $bloqueId);
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
	

	/**
     * 	Lista los bloques desde un AuditorioDia
     *
     * @Route("/bloques/{auditodiodia_id}", name="listar_bloques")
     * @Method("get")
     * 
	public function listarBloquesAction($auditodiodia_id) {
		
		try { 
			$auditorioDia  = $this->getEntity('CpmJovenesBundle:AuditorioDia', $auditodiodia_id);
			return $this->createJsonResponse($auditorioDia->toArray(2));			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
		
	}

     */
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
	public function getTipoPresentacionAction() {
		
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
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// PRESENTACIONES ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	     
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
	        	$presentacion->setBloque( $newBloque);
	       	}
	       	if (!empty($args['tanda'])){
	        	$nuevaTanda = $this->getEntity('CpmJovenesBundle:Tanda', $args['tanda']);
	        	
    			if($presentacion->getTanda() == null)
    				$nuevaTanda->addPresentacion($presentacion);
    			elseif (!$nuevaTanda->equals($presentacion->getTanda()))
    				$this->getChapaManager()->cambiarDeTanda($presentacion,$nuevaTanda);
	        }
	        //FIXME corregir posicion en bloque
		        //if (isset($args['posicion']))
				//	$presentacion->setPosicion((int) $args['posicion'] );
	        $em->persist($presentacion);
	        $em->flush();
	        
	        if($presentacion->esExterna()){
		        $presentacion  = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionExterna', $id);
		        
		        if (!empty($args['titulo']))
					$presentacion->setTitulo((string)$args['titulo']);
				
				if (isset($args['ejeTematico'])){
		        	$ejeTematico=$this->getEntity('CpmJovenesBundle:Tema', (int)$args['ejeTematico'] );
			        $presentacion->setEjeTematico( $ejeTematico );
		        }
		        
		        if (isset($args['areaReferencia'])){
		        	$areaReferencia=$this->getEntity('CpmJovenesBundle:Eje', (int)$args['areaReferencia'] );
			        $presentacion->setAreaReferencia( $areaReferencia );
		        }
		        
		        if (isset($args['tipoPresentacion'])){
		        	$tipoPresentacion=$this->getEntity('CpmJovenesBundle:Produccion', (int)$args['tipoPresentacion'] );
			        $presentacion->setTipoPresentacion( $tipoPresentacion );
		        }
		        
		        if (isset($args['escuela']))
					$presentacion->setEscuela( (string) $args['escuela'] );
	
		        if (isset($args['provincia']))
					$presentacion->setProvincia( (string) $args['provincia'] );
	
		        if (isset($args['localidad']))
					$presentacion->setLocalidad( (string) $args['localidad'] );
	
		        if (isset($args['distrito']))
					$presentacion->setDistrito( (string) $args['distrito'] );
	
		        if (isset($args['personasConfirmadas']))
					$presentacion->setPersonasConfirmadas((int) $args['personasConfirmadas'] );
					
				if (isset($args['apellidoCoordinador']))
					$presentacion->setApellidoCoordinador((int) $args['apellidoCoordinador'] );
				if (isset($args['nombreCoordinador']))
					$presentacion->setNombreCoordinador((int) $args['nombreCoordinador'] );
					
				$em->persist($presentacion);
	     	   	$em->flush();
	        }
		
	        
 			
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
			$presentacion = $this->getEntityForDelete('CpmJovenesBundle:PresentacionExterna', $id);
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($presentacion);
	        $em->flush();
			return $this->answerOk("Se eliminó con éxito la presentación externa número {$id}");			
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	//////////////////////////////////////////////////////////////////////////////
	///////////////////////////// MAS ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// INTERNOS ////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * Agrego estos dos helpers answerOk y answerError para concentrar los tipos de respuesta, por si cambiamos de JSON a http_code y vice versa
	 */
	private function answerOk($message="") {
		return $this->createJsonResponse(array('status' => 'success', 'message' => $message));
		//$response = $this->createSimpleResponse(200,$message);
		return $response->send();		
	}
	
	private function answerError($message) {
		if ($message instanceof \Exception)
			$message=$message->getMessage();
			
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
		
		if (method_exists($entity, 'toArray')){
			$resultArray=$entity->toArray($depth);
		}else{
			throw new \Exception();
			$reflectionEntity = new \ReflectionObject($entity);
			//TODO muy lindo pero no soporta ciclos ...
			$resultArray=array();
		    foreach ($reflectionEntity->getProperties(\ReflectionProperty::IS_PUBLIC + \ReflectionProperty::IS_PROTECTED) as $prop) {
				$pv = $prop->getValue();
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