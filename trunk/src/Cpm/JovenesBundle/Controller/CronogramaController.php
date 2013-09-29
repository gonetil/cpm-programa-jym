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
					$tandaArray['presentaciones_libres'][]=$p->toArray(1);
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
     * @Route("/dia") 
     * @Method("post")
     */
	public function crearDiaAction() {
		try { 
			$tandaId=$this->getRequest()->query->get('tandaId');
			$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tandaId);
			$dia = $tanda->agregarDia(-1);
			
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
			$tanda->eliminarDia($dia);
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
	       	if (!(empty($args['auditorio']) && isset($args['auditorio']['id'])))
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
		$vars = $this->getVarsFromJSON();

		$dia_origen_id = $vars['dia_origen'];
		$dia_destino_id = $vars['dia_destino'];
		//TODO validar parametros
		
		try {
			$dia_origen = $this->getEntity('CpmJovenesBundle:Dia', $dia_origen_id);
		}
		catch (\Exception $e) {
    		    return $this->answerError("Dia $dia_origen_id no encontrado"); //dia origen tiene que existir si o si		
    	}
    	
    	
    	$chapaManager = $this->getChapaManager();
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$em->getConnection()->beginTransaction();
    	
    	try { 
	    		$dia_destino = null;
	    		
	    		$numero_dia = null; //el numero del nuevo dia , si es la misma tanda debera calcularse a (max_dia + 1)
	    		
	    		if (!empty($dia_destino_id))
		    		try {
		    			
						$dia_destino = $this->getEntity('CpmJovenesBundle:Dia', $dia_destino_id);
						$chapaManager->vaciarDia($dia_destino); //saca los auditoriosDia y sus bloques, si los hubiera
						$numero_dia = $dia_destino->getNumero(); //el numero de dia ya venia con el dia
						$tanda = $tanda = $dia_destino->getTanda();
					}
					catch (\Exception $e) {
						$tanda = $dia_origen->getTanda();		
					}
		    	else
		    		$tanda = $dia_origen->getTanda();
		    	
		    	$dia_destino = $chapaManager->clonarDia($dia_origen,$tanda,$numero_dia,$dia_destino);
		    	$tanda->addDia($dia_destino);
   				$em->persist($tanda);
    			$em->flush();
    			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    	   	$em->getConnection()->rollback();
			$em->close();
            return $this->answerError($e);
    	}
    			
		return $this->createJsonResponse($dia_destino->toArray(3));
    	
	}
	
	
	
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