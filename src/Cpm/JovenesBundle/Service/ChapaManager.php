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
			//eliminar presentaciones
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
	    for($dia=1;$dia<=$dias;$dia++) {
			$tandaDia = $this->agregarDiaATanda($tanda,$dia);	       	
			//cargo los auditorios para cada dia
	       	foreach ( $auditorios as $auditorio)
	       		$this->doctrine->getEntityManager()->persist(new AuditorioDia($auditorio,$tandaDia));
			
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
