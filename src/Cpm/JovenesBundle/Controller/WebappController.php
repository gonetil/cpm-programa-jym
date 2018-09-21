<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Cpm\JovenesBundle\Entity\Tanda;
use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Exception\UserActionException;
use Cpm\JovenesBundle\Entity\AuditorioDia;


class WebappController extends BaseController {    

    /**
     * Retorna un JSON con la lista de tandas
     * 
     * @Route("/tanda", name="get_all_tandas")
     * @Method("get")
     */
    public function tandaAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $tandas = $em->getRepository('CpmJovenesBundle:Tanda')->findAllQuery( $this->getJYM()->getCicloActivo() )->getResult(); 
        $result = array();
        foreach($tandas as $tanda) {
            $result[] = $tanda->toArray(1);
        }
        return $this->createJsonResponse($result);
    }

    /** 
    * @Route("tanda/{id}", name="get_tanda")
    * @Method("get")
    */
    public function getTandasAction($id) {
		$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $id);
        if (!$tanda) 
            throw $this->createNotFoundException('Tanda no encontrada');
        
        return $this->createJsonResponse($this->tandaToEventsArray($tanda));
    }

    private function tandaToEventsArray($tanda) {
        $result = array();
        $result['tanda'] = array('tanda'=>$tanda->getNumero(), 
                                 'inicio' => $tanda->getFechaInicio(), 
                                 'fin' => $tanda->getFechaFin(),
                                 'dias' => array()
                                );

        foreach($tanda->getDias() as $dia) 
            $result['tanda']['dias'][] = $this->diaToEventsArray($dia,$tanda);

        return $result;
    }

    private function diaToEventsArray($dia,$tanda) {
        $dia_tanda = array();
        $fechaInicio = $tanda->getFechaInicio()->add( date_interval_create_from_date_string(($dia->getNumero() - 1).' days') );

        print_r($dia);
        $bloques = $dia->getBloques();
        
        foreach($bloques as $bloque)
            $dia_tanda['bloques'][] = $this->bloqueToEventsArray($bloque);

        return $dia_tanda;

    }

    private function bloqueToEventsArray($bloque) {
        $bloque_dia = array();
        $auditorio = $bloque->getAuditorioDia()->getAuditorio();
        $bloque_dia['nombre'] = $bloque->getNombre();
        $bloque_dia['ejes_tematicos'] = $bloque->getEjesTematicos();
        $bloque_dia['areas_referencia'] = $bloque->getAreasReferencia();
        $bloque_dia['horaInicio'] = $$bloque->getHoraInicio();;
        $bloque_dia['auditorio'] = $auditorio->getNombre();

        if ($bloque->getTienePresentaciones()) { 
            $bloque_dia['presentaciones'] = array();
            foreach($bloque->getPresentaciones() as $presentacion) 
                $bloque_dia['presentaciones'][] = $this->presentacionToEventsArray($presentacion);
        }

        return $bloque_dia;
    }

    private function presentacionToEventsArray($presentacion) {
        $presentacion_bloque = array();
        $presentacion_bloque['titulo'] = $presentacion->getTitulo();
        $presentacion_bloque['escuela'] = $presentacion->getEscuela();
        $presentacion_bloque['localidad'] = $presentacion->getLocalidad();
        $presentacion_bloque['distrito'] = $presentacion->getDistrito();
        $presentacion_bloque['tipo'] = $presentacion->getTipoPresentacion();
        return $presentacion_bloque;

    }
		
}