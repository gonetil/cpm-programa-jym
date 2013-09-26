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

    /**
     * Gets a Bloque entity
     * @Route("/bloque/{id}", name="mostrar_bloque")
     * @Method("get")
     */
     public function mostrarBloqueAction($id) {
     	try {
	     	$bloque  = $this->getEntity('CpmJovenesBundle:Bloque', $id);
			return $this->createJsonResponse($bloque->toArray(2));			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
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
			return $this->createJsonResponse($auditorioDia->toArray(2));			
		}
		catch (\Exception $e) {
			return $this->answerError($e);
		}
		
	}

	/**
     * @Route("/dia") 
     * @Method("post")
     */
	public function crearDiaAction() {
		
		$tandaId=$this->getRequest()->query->get('tandaId');
		$tanda  = $this->getEntity('CpmJovenesBundle:Tanda', $tandaId);
		$dia = $tanda->agregarDia(-1);
		
		try { 
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($dia);	
			$em->persist($tanda);
	        $em->flush();
			return $this->createJsonResponse($dia->toArray(2));
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
			$dia = $this->getEntity('CpmJovenesBundle:Dia', $diaId);
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
	
	
	/**
     * @Route("/tanda/{tandaId}/dia/{diaId}/auditorioDia") 
     * @Method("post")
     */
	public function crearAuditorioDiaAction($tandaId, $diaId) {
		$dia  = $this->getEntity('CpmJovenesBundle:Dia', $diaId);
		//FIXME conseguir el auditorioID
		$auditorioId = 1;
		$auditorio = $this->getEntity('CpmJovenesBundle:Auditorio', $auditorioId);

		$ad = new AuditorioDia();
		$ad->setAuditorio($auditorio);
		$ad->setDia($dia);
		$dia->addAuditorioDia($ad);
		
		try { 
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($ad);
			$em->persist($dia);	
	        $em->flush();
			return $this->createJsonResponse($ad->toArray(-1));
		} catch (\Exception $e) {
			return $this->answerError($e);
		}
	}
	
	/**
     * @Route("/tanda/{tandaId}/dia/{diaId}/auditorioDia/{auditorioDiaId}")
     * @Method("DELETE")
     */
	public function eliminarAuditorioDiaAction($tandaId, $diaId, $auditorioDiaId) {
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
	    
	        $args = $this->getVarsFromJSON();
	        
	        if (!empty($args['auditorioDia']))
	        	$bloque->setAuditorioDia( $this->getEntity('CpmJovenesBundle:AuditorioDia', $args['auditorioDia'] ) );
	        
	        if (!empty($args['horaInicio']))
				$bloque->setHoraInicio( date_create_from_format ('h:i', $args['horaInicio']));
			
			//FIXME corregir posicion del bloque
	        if (!empty($args['posicion']))
				$bloque->setPosicion( $args['posicion'] );

	        if (!empty($args['duracion']))
				$bloque->setDuracion( $args['duracion'] );

	        if (isset($args['nombre']))
				$bloque->setNombre( $args['nombre'] );

	        if (isset($args['tienePresentaciones']))
				$bloque->setTienePresentaciones( $args['tienePresentaciones'] );
			
/*			No toco las presentaciones del bloque. Para eso esta la interface loca
 *         if (isset($args['presentaciones'])){
				$bloque->setPresentaciones ( $p ) ;
	        }
 */	
 			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bloque);
	        $em->flush();
	        return $this->createJsonResponse($bloque->toArray(2,false));		
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
			$this->answerError($e);
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
		} catch (\Exception $e) {
			$this->answerError("Tanda $tanda_id no pudo encontrarse");
		}
		
		$vars = $this->getVarsFromJSON($this->getRequest());
		$tanda_json = $this->getVar($vars,'tanda');
		
		$tanda = json_decode($tanda_json,true);
		var_dump($tanda); die;
		
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
    		$tandasArrayadas=array();
    		foreach($tandas as $t ){
    			$tandasArrayadas[]=$t->toArray(1);
    		}
    		return $this->createJsonResponse($tandasArrayadas);			
		} catch (\Exception $e) {
			//TODO aca no habría que hacer un retirn de answerError???
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
		$vars = $this->getVarsFromJSON($this->getRequest());

		$dia_origen_id = $this->getVar($vars,'dia_origen');
		$dia_destino_id = $this->getVar($vars,'dia_destino');
		//$dia_destino_id = $this->getRequest()->get('dia_destino');
		
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
		return $this->createJsonResponse(array('status' => 'error 500', 'message' => $message));
		//$response = $this->createSimpleResponse(500,$message);
		//return $response->send();
	}
	
	
	private function getVar($array,$key) {
		return ( array_key_exists($key,$array) ? $array[$key] : '' );
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
		            echo ' - Sin errores';
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

}