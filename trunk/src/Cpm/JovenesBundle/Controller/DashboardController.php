<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Cpm\JovenesBundle\Controller\BaseController;

use Cpm\JovenesBundle\Service\EstadosManager;
/**
 * Dashboard controller.
 *
 * @Route("/dashboard")
 */
class DashboardController extends BaseController
{   /**
     * 
     * @Method("get")
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
    	$ciclo = $this->getJYM()->getCicloActivo();
    	$data = array(
    				'primera_vez' => $this->getSystemStats(), //primera vez coordinador/colaborador/alumnos
    				'usuarios' => $this->getUsuariosStats($ciclo),
    				'estados' => $this->getProyectoStats($ciclo),
    				'instancias' => $this->getInstanciasStats($ciclo)
    				);
    				
	  //  echo "<pre>";var_dump($data); die;
	    return $data;				
    }
    
    
    /**
     * calcula la cantidad de proyectos en cada estado
     */
    private function getProyectoStats($ciclo) {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$proyectos_estados = $em->createQuery('SELECT count(p.id),e.estado FROM CpmJovenesBundle:Proyecto p LEFT JOIN p.estadoActual e JOIN p.ciclo c Where c = :ciclo GROUP BY e.estado')->setParameter('ciclo',$ciclo)->getResult();
    	$estadosManager = $this->getEstadosManager();
    	$estados_array = EstadosManager::getEstados();
    	
    	foreach ( $proyectos_estados as $index=>$pe ) {
       		if ($pe['estado'] != NULL)
	       		$proyectos_estados[$index]['estado'] = $estados_array[$pe['estado']];
	       	else
	       		$proyectos_estados[$index]['estado'] = 'Iniciados';
		}
		
    	return $proyectos_estados;
    }
    
    
    /**
     * Calcula la cantidad de coordinadores distintos, colaboradores y alumnos que participan en el ciclo activo
     */
    private function getUsuariosStats($ciclo) {
    	$em = $this->getDoctrine()->getEntityManager();
    	$coordinadores = count( $em->createQuery('SELECT DISTINCT u.id FROM CpmJovenesBundle:Proyecto p JOIN p.coordinador u JOIN p.ciclo c Where c = :ciclo')->setParameter('ciclo',$ciclo)->getResult());
    	$colaboradores = $em->createQuery('SELECT count(u.id) FROM CpmJovenesBundle:Proyecto p JOIN p.colaboradores u JOIN p.ciclo c Where c = :ciclo')->setParameter('ciclo',$ciclo)->getSingleScalarResult();
    	$alumnos = $em->createQuery('SELECT SUM(p.nroAlumnos) FROM CpmJovenesBundle:Proyecto p JOIN p.ciclo c Where c = :ciclo')->setParameter('ciclo',$ciclo)->getSingleScalarResult();
    	 	
    	return array( 'coordinadores' => $coordinadores, 'colaboradores' => $colaboradores, 'alumnos'=>$alumnos );
    }
    
    /**
     * Calcula la cantidad de coordinadores distintos, colaboradores y alumnos que participan en el ciclo activo
     */
    private function getInstanciasStats($ciclo) {
    	
    	$qb=$this->getRepository('CpmJovenesBundle:InstanciaEvento')->createQueryBuilder('ie');
		$qb->innerJoin('ie.evento','e')->innerJoin('e.ciclo','c')->andWhere('c = :ciclo')->setParameter('ciclo',$ciclo);
		$qb->andWhere('ie.fechaInicio >= :now')->setParameter('now',new \Datetime());
		$qb->orderBy('ie.fechaInicio','ASC')->orderBy('ie.fechaFin','ASC')->setMaxResults(10);
	
		$em = $this->getDoctrine()->getEntityManager();
    	$invitaciones_confirmadas = $em->createQuery( 'SELECT ie.id Instancia, count(inv.id) Confirmaciones' .
    									' FROM CpmJovenesBundle:InstanciaEvento ie JOIN ie.evento e JOIN e.ciclo c JOIN ie.invitaciones inv' .
    									' WHERE inv.aceptoInvitacion = 1' .
    									' and c = :ciclo ')->setParameter('ciclo',$ciclo)->getResult();
		$confirmaciones = array();
		foreach ( $invitaciones_confirmadas as $inv ) {
       		$confirmaciones[$inv['Instancia']] = $inv['Confirmaciones'];
		}
	
		return array( 'instancias' => $qb->getQuery()->getResult(), 'confirmaciones' => $confirmaciones);
    }
    
}