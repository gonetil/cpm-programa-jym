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
    * @Route("tanda/{id}", name="get_single_tanda")
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
                'completada' => $tanda->getCompletada() ,
                "auditorios" => $this->getAuditoriosIndex($tanda->getDias()),
        );
        if ($recursive && $tanda->getCompletada() )
            $result['dias'] = $this->map('diasToEventsArray',$tanda->getDias());
        
         return $result;
    }

    /**
     * retorna un listado con todos los auditorios que se utilizarnan en los bloques de la tanda
     */
    private function getAuditoriosIndex($dias) {
        $auditorios = array();
        foreach($dias as $dia)
            foreach ($dia->getAuditoriosDia() as $auditorioDia) 
                $auditorios[] = array('id'=>$auditorioDia->getAuditorio()->getId(), 'nombre' => $auditorioDia->getAuditorio()->getNombre());
        return $auditorios;        

    }
    private function diasToEventsArray($dia) {
        
        $bloques = $this->map('auditorioDiaToEventsArray',$dia->getAuditoriosDia());
        $bloques_planos = array();
        foreach ($bloques as $bloque_array) 
            foreach($bloque_array as $b)
                $bloques_planos[] = $b;

        return array('numero'=> $dia->getNumero(), 
        'bloques' =>   $bloques_planos) ;
    }

    private function auditorioDiaToEventsArray($auditorioDia) {
        $id_auditorio = $auditorioDia->getAuditorio()->getId();
        
        $sort_fn = function($b1,$b2) { 
            $hora1 = new \DateTime($b1->getHoraInicio()->format('H:i'));
            $hora2 = new \DateTime($b2->getHoraInicio()->format('H:i'));
            return ($hora1 <= $hora2);
        };

        $bloques = $this->map('bloqueToEventsArray',$auditorioDia->getBloques(), $sort_fn);
        for($i=0;$i<count($bloques);$bloques[$i++]['auditorio']=$id_auditorio);

      

///            $b['auditorio'] = $id_auditorio;
        return $bloques;


    }

    private function bloqueToEventsArray($bloque) {
        
        $sorter = function($p1,$p2) { return ($p1->getPosicion() < $p2->getPosicion()) ; };
        $presentaciones = array();
        foreach ( $this->map( 'presentacionToEventsArray' , $bloque->getPresentaciones() , $sorter) as $p)
            $presentaciones[] = $p;
        return array(
                'nombre' => $bloque->getNombre(),
                'horaInicio' => $bloque->getHoraInicio()->format('H:i'),
//                'duracion' => $bloque->getDuracion(),
                'horaFin' => $bloque->getHoraInicio()->modify("+{$bloque->getDuracion()} minutes")->format('H:i'),
                'presentaciones' => $presentaciones 
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
            //'posicion' => $presentacion->getPosicion()
            //'distrito' => $presentacion->getDistrito();
        );
    }

    /**
     * Helper function para ejecutar el array_map sobre colecciones 
     */

    private function map($fn,$doctrine_collection, $sort_fn = null) {
        $array = $doctrine_collection->toArray();
        if ($sort_fn)
            uasort($array, $sort_fn);

        return array_map( 
                array ( $this,$fn) , $array );
    }

    private function safeString($string) {
       $acentos = array( 'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ñ'=>'N', 'Ó'=>'O', 'Ú'=>'U', 'á'=>'a', 'é'=>'e', 'í'=>'i', 'ñ'=>'n', 'ó'=>'o', 'ú'=>'u' );
       return strtr($string, $acentos);
    }
}