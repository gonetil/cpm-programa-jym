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
        $result = array_map( array($this,'tandaToEventsArray'),$tandas);

        return $this->getJSON( $result );
    }

    /**
     * Retorna un JSON con la lista de tandas
     * 
     * @Route("/tanda/all", name="get_all_tandas_full")
     * @Method("get")
     */
    public function allTandaAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $tandas = $em->getRepository('CpmJovenesBundle:Tanda')->findAllQuery( $this->getJYM()->getCicloActivo() )->getResult(); 
        $result = array();
        foreach($tandas as $tanda) 
            $result[] = $this->tandaToEventsArray($tanda,true);
        
        return $this->getJSON( $result );
    }

    /** 
    * @Route("tanda/{id}", name="get_tanda")
    * @Method("get")
    */
    public function getTandaAction($id) {
		$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $id);
        if (!$tanda) 
            throw $this->createNotFoundException('Tanda no encontrada');
        
        return $this->getJSON( $this->tandaToEventsArray($tanda, true) );
    }







    /****** HELPER FUNCTIONS TO WALK THROUGH TANDAS ***********/

    private function getJSON($array) {
        return $this->createJsonResponse($array, "charset=utf-8");
    }


    private function tandaToEventsArray($tanda,$recursive = false) {
        $result = array(
                'id' => $tanda->getId(),
                'numero'=>$tanda->getNumero(), 
                'fechaInicio' => $tanda->getFechaInicio()->format('Y-m-d'), 
                'fechaFin' => $tanda->getFechaFin()->format('Y-m-d'),
        );
        if ($recursive)
            $result['dias'] = $this->map('diasToEventsArray',$tanda->getDias());
        
         return $result;
    }

    private function diasToEventsArray($dia) {
        return array('numero'=> $dia->getNumero(), 'auditoriosDia' =>  $this->map('auditorioDiaToEventsArray',$dia->getAuditoriosDia()) ) ;
    }

    private function auditorioDiaToEventsArray($auditorioDia) {
        return array(  
            'auditorio' => $auditorioDia->getAuditorio()->getNombre(),
            'bloques' => $this->map('bloqueToEventsArray',$auditorioDia->getBloques()) 
            );

    }

    private function bloqueToEventsArray($bloque) {
        return array(
                'nombre' => $bloque->getNombre(),
                'horaInicio' => $bloque->getHoraInicio()->format('H:i'),
                'duracion' => $bloque->getDuracion(),
                'presentaciones' => $this->map( 'presentacionToEventsArray' ,$bloque->getPresentaciones() ),
   //           'ejes_tematicos' => $bloque->getEjesTematicos(),
   //           'areas_referencia' => $bloque->getAreasReferencia(),
        );

    }

    private function presentacionToEventsArray($presentacion) {
        return array(
            'id' => $presentacion->getId(),
            'titulo' => trim( $presentacion->getTitulo(),' '),
            'escuela' => $presentacion->getEscuela(),
            'localidad' => $presentacion->getLocalidad(),
            'tipo' => $this->safeString($presentacion->getTipoPresentacion()->getTipoPresentacion()),
            //'distrito' => $presentacion->getDistrito();
        );
    }

    /**
     * Helper function para ejecutar el array_map sobre colecciones 
     */

    private function map($fn,$doctrine_collection) {
        return array_map( 
                array ( $this,$fn) , 
                $doctrine_collection->toArray() 
            );
    }

    private function safeString($string) {
       $acentos = array( 'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ñ'=>'N', 'Ó'=>'O', 'Ú'=>'U', 'á'=>'a', 'é'=>'e', 'í'=>'i', 'ñ'=>'n', 'ó'=>'o', 'ú'=>'u' );
       return strtr($string, $acentos);
    }
}