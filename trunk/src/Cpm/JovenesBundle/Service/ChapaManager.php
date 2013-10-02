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
		$dia->setAuditoriosDia( array_map( array($this,'resetAuditorioDia') , $dia->getAuditoriosDia()->toArray() ) );
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
		$nuevo_bloque->setNombre($bloque->getNombre());
		$nuevo_bloque->setPosicion($bloque->getPosicion());
		$nuevo_bloque->setTienePresentaciones($bloque->getTienePresentaciones());
		$nuevo_bloque->setDuracion($bloque->getDuracion());
		$nuevo_bloque->setHoraInicio($bloque->getHoraInicio());
		$nuevo_bloque->setAuditorioDia($bloque->getAuditorioDia());	
		$nuevo_bloque->setEjesTematicos($bloque->getEjesTematicos());
		$nuevo_bloque->setAreasReferencia($bloque->getAreasReferencia());
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
	
	/**
	 * genera una nueva tanda con los mismos dias, auditoriosDia y bloques de $tanda, y la asigna a la instanciaEvento $instancia
	 */
	public function clonarTanda($tanda,$instancia) {
		
		$new_tanda = new Tanda($instancia);
		
		$new_tanda->setNumero($this->getNextTandaNumber($tanda));
		foreach ( $tanda->getDias() as $dia ) 
       		$new_tanda->addDia($this->clonarDia($dia));
		
		$em = $this->doctrine->getEntityManager();
		$em->getConnection()->beginTransaction();
    	try { 
	        $em->persist($new_tanda);
	        $em->flush();		
			$em->getConnection()->commit();
			return $new_tanda;
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}
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
       		$tanda->addPresentacion($presentacion);
		}
	}
	
	public function inicializarUnaTanda($instancia,$incluir_no_confirmadas,$numero=0,$auditorios = null) {

		$em = $this->doctrine->getEntityManager();
		$tanda = new Tanda($instancia);
	    $tanda->setNumero($numero);
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
	
	/**
	 * Elimina un bloque. Antes de hacerlo, desasocia todas las presentaciones del mismo (quedarán asociadas a la tanda)
	 * FIMXE alguien usa esta funcion?
	 */
	private function borrarBloque($bloque) {
		
    	$em = $this->doctrine->getEntityManager();
    	$em->getConnection()->beginTransaction();		
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
	
	
	
}