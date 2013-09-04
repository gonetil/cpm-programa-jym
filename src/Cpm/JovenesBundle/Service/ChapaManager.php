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

	public function resetBloque($bloque) {
		if ($bloque->getTienePresentaciones()) {
			foreach ( $bloque->getPresentaciones() as $presentacion ) {
				$presentacion->setBloque(null);       
			}
			//TODO faltaria eliminar la presentacion de la lista de presentaciones del bloque
		}
		return $bloque;
	}
	
	public function resetAuditorioDia($auditorioDia) {
		$auditorioDia->setBloques( array_map( array($this,'resetBloque') , $auditorioDia->getBloques()));
		return $auditorioDia;	
	}

	public function resetDia($dia) {
		$dia->setAuditoriosDias( array_map( array($this,'resetAuditorioDia') , $dia->getAuditoriosDias()) );
	    return $dia;
	}
	
	public function crearDiasParaTanda($tanda,$num_dias,$auditorios) {
	    //creo los dias para la tanda
	    for($dia=1;$dia<=$num_dias;$dia++) {
			$tandaDia = Dia::createDia($tanda,$dia);	       	
			//cargo los auditorios para cada dia
	       	foreach ( $auditorios as $auditorio)
	       		$this->doctrine->getEntityManager()->persist(new AuditorioDia($auditorio,$tandaDia));
			
	     }
	}
	
	
	public function crearPresentacionesParaTanda($tanda) {
		$em = $this->doctrine->getEntityManager();
		
		$invitaciones = $em->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesAceptadas($tanda->getInstanciaEvento());
		foreach ( $invitaciones as $invitacion ) {
       		$presentacion = PresentacionInterna::createFromProyecto($invitacion[0]->getProyecto());
       		$presentacion->setTanda($tanda);
       		$tanda->addPresentacion($presentacion);
		}
	}
	
	public function inicializarUnaTanda($instancia,$numero=0,$auditorios = null) {

		$em = $this->doctrine->getEntityManager();
		$tanda = Tanda::createFromInstancia($instancia,$numero);
	    
	    if ($auditorios == null)
	    	$auditorios = $em->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado'=>false));
	    	   		
	    $diff = $instancia->getFechaInicio()->diff($instancia->getFechaFin(), true); 
	    $dias = $diff->days + 1; //se consideran los dias entre las fechas mas el primer dia
	    
	    $this->crearDiasParaTanda($tanda,$dias,$auditorios);
	    $this->crearPresentacionesParaTanda($tanda);
	       		
	    return $tanda; 
	}
	
	public function inicializarTandas($evento) {
		$em = $this->doctrine->getEntityManager();
		
		$instancias = $evento->getInstancias();
    	if (count($instancias) < 1) {
    		throw $this->createNotFoundException('El evento seleccionado no posee instancias');
    	}
    	$iterator = $instancias->getIterator();
    	$iterator->uasort(function($inst1,$inst2) { return ($inst1->getFechaInicio() < $inst2->getFechaInicio()) ? -1 : 1 ; });
    	$index = 1;
  
	  	$auditorios = $em->getRepository('CpmJovenesBundle:Auditorio')->findBy(array('anulado'=>false));
	  	
	  	$em->getConnection()->beginTransaction();
    	try { 
	    	foreach ( $instancias as $instancia ) {
	    		$tanda = $this->inicializarUnaTanda($instancia,$index,$auditorios);
	            $em->persist($tanda);
	            $em->flush();
	            $index++;
			}
			$em->getConnection()->commit();
			
    	} catch (\Exception $e) {
    		$em->getConnection()->rollback();
			$em->close();
            throw $e;
    	}  	
	}
	

}
