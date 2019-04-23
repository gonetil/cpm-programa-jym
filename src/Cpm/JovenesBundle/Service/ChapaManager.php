<?php
/*
 * Created on 04/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Tanda;
use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Entity\AuditorioDia;
use Cpm\JovenesBundle\Entity\Bloque;
use Cpm\JovenesBundle\Entity\Auditorio;
use Cpm\JovenesBundle\Entity\Presentacion;
use Cpm\JovenesBundle\Entity\PresentacionInterna;
use Cpm\JovenesBundle\Entity\PresentacionExterna;
use Cpm\JovenesBundle\Exception\UserActionException;



class ChapaManager {
	
	protected $jym;
    protected $doctrine;
    protected $logger;
    
    public function __construct(JYM $jym, $doctrine, $logger)
    {
        $this->jym = $jym;
	    $this->doctrine = $doctrine;
	    $this->logger = $logger;
    }


	/*** FUNCIONES DE CLONACION **********/

	/**
	 * retorna un bloque igual a $bloque pero sin presentaciones
	 */
	public function clonarBloque($bloque,$auditorioDia=null) {
		$nuevo_bloque = new Bloque();
		$nuevo_bloque->setNombre($bloque->getNombre());
		$nuevo_bloque->setDescripcion($bloque->getDescripcion());
		$nuevo_bloque->setTienePresentaciones($bloque->getTienePresentaciones());
		$nuevo_bloque->setDuracion($bloque->getDuracion());
		$nuevo_bloque->setHoraInicio($bloque->getHoraInicio());
		$nuevo_bloque->setAuditorioDia($bloque->getAuditorioDia());	
		$nuevo_bloque->setEjesTematicos($bloque->getEjesTematicos()->toArray());
		$nuevo_bloque->setAreasReferencia($bloque->getAreasReferencia()->toArray());
		
		//$nuevo_bloque = clone $bloque;
		//$nuevo_bloque->setId(null);
		return $nuevo_bloque;
	}
	
	public function clonarAuditorioDia($auditorioDia) {
		$newAuditorioDia = new AuditorioDia();
		$newAuditorioDia->setAuditorio($auditorioDia->getAuditorio());
		foreach ( $auditorioDia->getBloques() as $bloque )
       		$newAuditorioDia->addBloque($this->clonarBloque($bloque));
		
		return $newAuditorioDia;		
	}
	
	/**
	 * genera un nuevo dia, con la misma cantidad de auditorioDia (mismos auditorios), y los mismos bloques para cada auditorioDia
	 */
	public function clonarDia($dia,$new_dia = null) {
		if ($new_dia == null)  
			$new_dia = new Dia();
		
		//quito los ad viejos
		foreach ( $new_dia->getAuditoriosDia() as $auditorioDia )
			$new_dia->removeAuditorioDia($auditorioDia);
		
		//clono los ad nuevos
		foreach ( $dia->getAuditoriosDia() as $auditorioDia )
       	 	$new_dia->addAuditorioDia($this->clonarAuditorioDia($auditorioDia));
	
		return $new_dia;
	}
	
	
	private function getNextTandaNumber($tanda) {
		$evento = $tanda->getInstanciaEvento()->getEvento();
		
		$tandas = $this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Tanda')->getTandasDeEvento($evento);
		$max = 0;
		foreach ( $tandas as $t) {
       		$max =  ( $t->getNumero() >= $max ) ? $t->getNumero() : $max; 
		}
		return $max+1;
	}
	
	/*** FIN FUNCIONES DE CLONACION **********/
	
	
	
	/*** FUNCIONES DE INICIALIZACION AUTOMATICA **********/
	private function crearDiasParaTanda($tanda,$num_dias) {
        $em = $this->doctrine->getEntityManager();
	    $auditorios = $em->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado'=>false));
		//creo los dias para la tanda
	    for($nroDia=1;$nroDia<=$num_dias;$nroDia++) {
			$tandaDia = new Dia($nroDia);	
			$tanda->addDia($tandaDia);        	
			//cargo los auditorios para cada dia
	       	foreach ( $auditorios as $auditorio) { 
	       		$newAuditorioDia = new AuditorioDia();
				$newAuditorioDia->setAuditorio($auditorio);
				$tandaDia->addAuditorioDia($newAuditorioDia);
	       	}
		 }
	}
	
	private function crearTandaParaInstancia($instancia,$numero, $tandaTemplate = null) {

	  	$tanda = new Tanda($instancia);
	    $tanda->setNumero($numero);
	    if ($tandaTemplate ==null){ 
  	 	    $dias = $instancia->getCantDias();
   		    $this->crearDiasParaTanda($tanda,$dias);
		}else{
			foreach ( $tandaTemplate->getDias() as $dia ) 
	       		$tanda->addDia($this->clonarDia($dia));
		}
		return $tanda; 
	}
	
	private function crearPresentacionesParaTanda($tanda,$incluir_no_confirmadas) {
		$em = $this->doctrine->getEntityManager();
		
		$invitaciones = $em->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesAceptadas($tanda->getInstanciaEvento(),$incluir_no_confirmadas);
		foreach ( $invitaciones as $invitacion ) {
       		$presentacion = new PresentacionInterna($invitacion[0]);
       		$tanda->addPresentacion($presentacion);
		}
	}
	public function inicializarRestoTandas($instanciasIterator,$incluir_no_confirmadas) {
		$em = $this->doctrine->getEntityManager();
		//consumo y guardo la primer instancia
		$primerTanda = $em->getRepository('CpmJovenesBundle:Tanda')->findOneBy(array('instanciaEvento'=>$instanciasIterator->current()->getId()));
		if ($primerTanda == null)
    		throw new UserActionException('Debe crear y configurar la primer tanda y luego generar el resto de las tandas');
	  	$instanciasIterator->next();
	  	
		$index = 1;
  		$created = 0;
	  	$em->getConnection()->beginTransaction();
	  	
	  	
    	try { 
	    	foreach ( $instanciasIterator as $instancia ) {
				$tanda = $em->getRepository('CpmJovenesBundle:Tanda')->findOneBy(array('instanciaEvento'=>$instancia->getId()));
				if (empty($tanda)) {
		    		$tanda = $this->crearTandaParaInstancia($instancia,$index,$primerTanda);
	            	
	            	$created++;
				} else{
					//la tanda ya existe
				}
				
				if ($tanda->getPresentaciones()->isEmpty()){
					//si la tanda no tiene presentaciones trato de generárselas
					$this->crearPresentacionesParaTanda($tanda,$incluir_no_confirmadas);
				}
				$em->persist($tanda);
            	$em->flush();
	            $index++;
			}
			$index--;
			$em->getConnection()->commit();
			
    	} catch (\Exception $e) {
    		if ($em->getConnection()->isTransactionActive())
	    	   	$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}  	
    	
    	return "$index instancias analizadas, $created tandas creadas";
	}
	
	public function inicializarPrimerTanda($instanciasIterator,$incluir_no_confirmadas) {
		$em = $this->doctrine->getEntityManager();
        $instancia = $instanciasIterator->current();

    	$primerTanda = $em->getRepository('CpmJovenesBundle:Tanda')->findOneBy(array('instanciaEvento'=>$instancia->getId()));
		if ($primerTanda != null)
    		throw new UserActionException('la primer tanda del evento ya existe, no se hace nada.');
    	
    	$em->getConnection()->beginTransaction();
    	try { 
	    	$primerTanda = $this->crearTandaParaInstancia($instancia,1);
	        $this->crearPresentacionesParaTanda($primerTanda,$incluir_no_confirmadas);
	        $em->persist($primerTanda);
		    $em->flush();
	    	$em->getConnection()->commit();
			return "Se creó la primer tanda";
			
    	} catch (\Exception $e) {
    		if ($em->getConnection()->isTransactionActive())
	    	   	$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}  	
    	
	}
	public function inicializarTandas($evento,$incluir_no_confirmadas, $una_o_resto = true) {
		$em = $this->doctrine->getEntityManager();
		$instanciasIterator = $evento->getInstanciasIterator();
    	if ($instanciasIterator->count() < 1) 
    		throw new UserActionException('El evento seleccionado no posee instancias');
    	$instanciasIterator->seek(0);
    	$eventoStr=" para el evento ".$evento->getTitulo();
    	if ($una_o_resto){
    		return $this->inicializarPrimerTanda($instanciasIterator, $incluir_no_confirmadas).$eventoStr;
    	}else{
    		return $this->inicializarRestoTandas($instanciasIterator, $incluir_no_confirmadas).$eventoStr;
    	}
    	
	}
	
	
	/*** FIN FUNCIONES DE INICIALIZACION AUTOMATICA **********/
	
	
	//TODO falta probar
	public function cambiarPresentacionDeTanda($presentacion,$tandaNueva) {
		$tandaVieja = $presentacion->getTanda();
		if($tandaVieja->equals($tandaNueva)){
			return ;
		}
		
    	$em = $this->doctrine->getEntityManager();
    	try {   		
			$em->getConnection()->beginTransaction();		
			$tandaVieja->removePresentacion($presentacion);
			$tandaNueva->addPresentacion($presentacion);
			
			$instancia = $tandaNueva->getInstanciaEvento();
			$invitacion = $presentacion->getInvitacion();
			if (($instancia) && (isset($invitacion))) //solo las presentaciones internas tienen invitacion e instancia 
			{
				$invitacion->setInstanciaEvento($instancia);
				$em->persist($invitacion);
			}	
			$em->persist($presentacion);
			$em->persist($tandaNueva);
    		$em->persist($tandaVieja);
			
			$em->flush();
			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
	}
	
		
	public function guardarRedistribucionDeTanda($tanda, $presentacionesDTO) {
		
		$cambios=0;
		$em = $this->doctrine->getEntityManager();
		$em->getConnection()->beginTransaction();
		
    	try { 
	        foreach($presentacionesDTO as $presentacionDTO){
	        	
	        	$presentacion = $em->getRepository('CpmJovenesBundle:Presentacion')->find($presentacionDTO['presentacion']);
	        	$oldBloque=$presentacion->getBloque();
	        	
				if (empty($presentacionDTO['bloque'])){
					if($oldBloque != null) {
						$oldBloque->removePresentacion($presentacion);
						$presentacion->setPosicion(0);
					}
				}else{
					$bloque=$em->getRepository('CpmJovenesBundle:Bloque')->find($presentacionDTO['bloque']);
					
					if($oldBloque != null){
						if(!$oldBloque->equals($bloque)){
							$oldBloque->removePresentacion($presentacion);
							$bloque->addPresentacion($presentacion);
							$presentacion->setPosicion(count($bloque->getPresentaciones()));
						}
					}else{
						$bloque->addPresentacion($presentacion);
						$presentacion->setPosicion(count($bloque->getPresentaciones()));
					}
				}
				if($oldBloque !== $presentacion->getBloque()){
					$cambios++;				
		        	$em->persist($presentacion);
				}
			}
	
	        $em->flush();		
			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    		if ($em->getConnection()->isTransactionActive())
	    	   	$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
		return $cambios;
	}
	
	
	public function resetearPresentacionesDeTanda($tanda) {
		
		$em = $this->doctrine->getEntityManager();
		$em->getConnection()->beginTransaction();
    	try {
    		$numUpdated=$em->getRepository('CpmJovenesBundle:Presentacion')->resetearPresentacionesDeTanda($tanda);
    		//$em->persist($tanda);
	        $em->flush();		
			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    		if ($em->getConnection()->isTransactionActive())
	    	   	$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
		return $numUpdated;	
	}
	


	/**
	 * Dada una $tanda preexistente, revisa la lista de presentaciones y la compara con los proyectos de la instancia de evento Chapa
	 * que se corresponde con la tanda.
	 * Con esta información:
	 * a) Elimina las presentaciones internas que no se corresponden con proyectos de la instancia de evento (esto no aplica a las externas)
	 * b) Crea y agrega las presentaciones que se corresponden con proyectos que confirmaron su asistencia a Chapa y que aún no figuran como presentaciones de la tanda
	 * 
	 */
	public function resincronizarTanda($tanda) {
		$cantAgregadas = $this->generarPresentacionesFaltantes($tanda);
		return $cantAgregadas;
//		$cantEliminadas = $this->eliminarPresentacionesMovidas($tanda);
	}


	private function generarPresentacionesFaltantes($tanda) {
		$incluir_no_confirmadas = true;
		$em = $this->doctrine->getEntityManager();
		$invitaciones = $em->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesAceptadas($tanda->getInstanciaEvento(),$incluir_no_confirmadas);
		$presentacionesInternas = array_filter($tanda->getPresentaciones()->toArray(), function($p) { return ($p->getTipo() == 'interna'); });

		$count = 0;
		foreach ( $invitaciones as $invitacion ) {
			if (! $this->findInvitacion($invitacion[0],$presentacionesInternas)) {
				$presentacion = new PresentacionInterna($invitacion[0]);
				$tanda->addPresentacion($presentacion);
				$count++;
			}
		}
		return $count;

	}

	private function findInvitacion($invitacion,$presentacionesInternas) {
		foreach( $presentacionesInternas as $presentacion)
			if ($presentacion->getInvitacion() == $invitacion)
				return TRUE;
		return FALSE;
	}
}
