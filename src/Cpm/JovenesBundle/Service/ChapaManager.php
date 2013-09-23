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


/*** FUNCIONES DE RESET **********/
	public function resetBloque($bloque) {
		if ($bloque->getTienePresentaciones()) {
			
			foreach ( $bloque->getPresentaciones() as $presentacion ) {
				$presentacion->setBloque(null);       
			}

			$bloque->setPresentaciones(array());

		}
		return $bloque;
	}
	
	public function resetAuditorioDia($auditorioDia) {
		 
		$auditorioDia->setBloques( array_map( array($this,'resetBloque') , $auditorioDia->getBloques()->toArray() ));
		return $auditorioDia;	
	}

	public function resetDia($dia) {
		$dia->setAuditoriosDias( array_map( array($this,'resetAuditorioDia') , $dia->getAuditoriosDias()->toArray() ) );
	    return $dia;
	}
	
	public function resetTanda($tanda) {
		
        $tanda->setDias( array_map( array($this,'resetDia') , $tanda->getDias()->toArray() )  );
        
		$em = $this->doctrine->getEntityManager();
		$em->getConnection()->beginTransaction();
    	try { 
	        $em->persist($tanda);
	        $em->flush();		
			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
		
	}
	
	/*** FIN FUNCIONES DE RESET **********/



	/*** FUNCIONES DE CLONACION **********/

	/**
	 * retorna un bloque igual a $bloque pero sin presentaciones
	 */
	public function clonarBloque($bloque,$auditorioDia=null) {
		$nuevo_bloque = new Bloque();
		$nuevo_bloque->setDuracion($bloque->getDuracion());
		$nuevo_bloque->setHoraInicio($bloque->getHoraInicio());
		$nuevo_bloque->setPosicion($bloque->getPosicion());
		$nuevo_bloque->setNombre($bloque->getNombre());
		$nuevo_bloque->setTienePresentaciones($bloque->getTienePresentaciones());
		if ($auditorioDia != null)
			$nuevo_bloque->setAuditorioDia($auditorioDia);
		
		return $nuevo_bloque;
	}
	
	public function clonarAuditorioDia($auditorioDia,$new_dia) {
		$newAuditorioDia = new AuditorioDia();
		$newAuditorioDia->setAuditorio($auditorioDia->getAuditorio());
		$newAuditorioDia->setDia($new_dia);
		foreach ( $auditorioDia->getBloques() as $bloque )
       		$newAuditorioDia->addBloque($this->clonarBloque($bloque,$newAuditorioDia));
		
		return $newAuditorioDia;		
	}
	
	/**
	 * genera un nuevo dia, con la misma cantidad de auditorioDia (mismos auditorios), y los mismos bloques para cada auditorioDia
	 */
	public function clonarDia($dia,$tanda,$numero_dia = null,$new_dia = null) {
		if ($new_dia == null) { 
			$new_dia = new Dia();
			$new_dia->setTanda($tanda);
			$new_dia->setNumero( ($numero_dia == null) ? $this->getNextDiaNumber($tanda) : $numero_dia );
			$tanda->addDia($new_dia);
		}
		
		foreach ( $dia->getAuditoriosDias() as $auditorioDia )
       	 	$new_dia->addAuditorioDia($this->clonarAuditorioDia($auditorioDia,$new_dia));
	
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
	
	private function getNextDiaNumber($tanda) {
		$max = 0;
		foreach ( $tanda->getDias() as $dia) {
       		$max = ($dia->getNumero() > $max ) ? $dia->getNumero() : $max;
		}
		return $max+1;
	}
	
	/**
	 * genera una nueva tanda con los mismos dias, auditoriosDias y bloques de $tanda, y la asigna a la instanciaEvento $instancia
	 */
	public function clonarTanda($tanda,$instancia) {
		
		$new_tanda = Tanda::createFromInstancia($instancia);
		
		$new_tanda->setNumero($this->getNextTandaNumber($tanda));
//		$new_tanda->setNumero( $tanda->getNumero() + 1 );
		foreach ( $tanda->getDias() as $dia ) 
       		$new_tanda->addDia($this->clonarDia($dia,$new_tanda));
		
	
		$em = $this->doctrine->getEntityManager();
		$em->getConnection()->beginTransaction();
    	try { 
	        $em->persist($new_tanda);
	        $em->flush();		
			$em->getConnection()->commit();
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
    	return $new_tanda;
		
    	
	}
	/*** FIN FUNCIONES DE CLONACION **********/
	
	
	
	/*** FUNCIONES DE INICIALIZACION AUTOMATICA **********/
	public function crearDiasParaTanda($tanda,$num_dias,$auditorios) {
	    //creo los dias para la tanda
	    for($dia=1;$dia<=$num_dias;$dia++) {
			$tandaDia = Dia::createDia($tanda,$dia);	       	
			//cargo los auditorios para cada dia
	       	foreach ( $auditorios as $auditorio) { 
	       		$newAuditorioDia = new AuditorioDia();
				$newAuditorioDia->setAuditorio($auditorio);
				$newAuditorioDia->setDia($tandaDia);
				$this->doctrine->getEntityManager()->persist($newAuditorioDia);
	       	}
	       		
		 }
	}
	
	
	public function crearPresentacionesParaTanda($tanda,$incluir_no_confirmadas) {
		$em = $this->doctrine->getEntityManager();
		
		$invitaciones = $em->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesAceptadas($tanda->getInstanciaEvento(),$incluir_no_confirmadas);
		foreach ( $invitaciones as $invitacion ) {
       		$presentacion = PresentacionInterna::createFromInvitacion($invitacion[0]);
       		$presentacion->setTanda($tanda);
       		$tanda->addPresentacion($presentacion);
		}
	}
	
	public function inicializarUnaTanda($instancia,$incluir_no_confirmadas,$numero=0,$auditorios = null) {

		$em = $this->doctrine->getEntityManager();
		$tanda = Tanda::createFromInstancia($instancia,$numero);
	    
	    if ($auditorios == null)
	    	$auditorios = $em->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado'=>false));
	    	   		
	    $diff = $instancia->getFechaInicio()->diff($instancia->getFechaFin(), true); 
	    $dias = $diff->days + 1; //se consideran los dias entre las fechas mas el primer dia
	    
	    $this->crearDiasParaTanda($tanda,$dias,$auditorios);
	    $this->crearPresentacionesParaTanda($tanda,$incluir_no_confirmadas);
	       		
	    return $tanda; 
	}
	
	public function inicializarTandas($evento,$incluir_no_confirmadas) {
		$em = $this->doctrine->getEntityManager();
		
		$instancias = $evento->getInstancias();
    	if (count($instancias) < 1) {
    		throw $this->createNotFoundException('El evento seleccionado no posee instancias');
    	}
    	$iterator = $instancias->getIterator();
    	$iterator->uasort(function($inst1,$inst2) { return ($inst1->getFechaInicio() < $inst2->getFechaInicio()) ? -1 : 1 ; });
    	$index = 1;
  		$created = 0;
	  	$auditorios = $em->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado'=>false));
	  	
	  	$em->getConnection()->beginTransaction();
    	try { 
	    	foreach ( $instancias as $instancia ) {

				//echo "buscando tanda para instanciaEvento ".$instancia->getId();
	    		$tanda = $em->getRepository('CpmJovenesBundle:Tanda')->findBy(array('instanciaEvento'=>$instancia->getId()));
	    		//echo "===>se encontraron ".count($tanda)." tandas <br/>";
				if (count($tanda) == 0) {
		    		$tanda = $this->inicializarUnaTanda($instancia,$incluir_no_confirmadas,$index,$auditorios);
	            	$em->persist($tanda);
	            	$em->flush();
	            	$created++;
				} 
	            $index++;
			}
			$index--;
			$em->getConnection()->commit();
			
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}  	
    	
    	return "$index instancias analizadas, $created tandas creadas";
	}
	
	/*** FIN FUNCIONES DE INICIALIZACION AUTOMATICA **********/
	
	
	//TODO falta probar
	public function cambiarDeTanda($presentacion,$tanda) {
		$invitacion = $presentacion->getInvitacion();
		$instancia = $tanda->getInstanciaEvento();
		
		$presentacion->setBloque(null);
		$presentacion->setTanda($tanda);
		$tanda->addPresentacion($presentacion);
		
    	$em = $this->doctrine->getEntityManager();
    	$em->getConnection()->beginTransaction();		
		try {   		
			if (($instancia) && (isset($invitacion))) //solo las presentaciones internas tienen invitacion e instancia 
			{
				$invitacion->setInstanciaEvento($instancia);
				$em->persist($invitacion);
			}	
			$em->persist($tanda);
    		$em->persist($presentacion);
			
			$em->flush();
			$em->getConnection()->commit();
    		return "Tanda cambiada satisfactoriamente";
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
		
	}
	
	/**
	 * Elimina un bloque. Antes de hacerlo, desasocia todas las presentaciones del mismo (quedarán asociadas a la tanda)
	 */
	public function borrarBloque($bloque) {
		
    	$em = $this->doctrine->getEntityManager();
    	$em->getConnection()->beginTransaction();		
		try {
			foreach ( $bloque->getPresentaciones() as $index => $presentacion ) {
		       	$presentacion->setBloque(null);
		       	$bloque->getPresentaciones()->remove($index);
		       	$em->persist($presentacion);
			}
		    $em->remove($bloque);
		    $em->flush();
		    $em->getConnection()->commit();
	    	return "Bloque eliminado satisfactoriamente";
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
	}
	
	
	/**
	 * elimina todos los auditoriosDias y sus bloques de un determinado dia
	 */
	public function vaciarDia($dia) {
		$em = $this->doctrine->getEntityManager();
		$this->resetDia($dia); //saco todas las presentaciones
					
		foreach ( $dia->getAuditoriosDias() as $index => $ad ) { //volamos todos los auditorios_dias del dia
		       	//$dia->getAuditoriosDias()->remove($index);
		       	$em->remove($ad);
		}
		$em->flush();
		return $dia;
	}
}
