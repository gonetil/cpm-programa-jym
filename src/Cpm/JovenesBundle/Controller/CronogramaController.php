<?php
/*
 * Created on 17/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Bloque controller.
 *
 * @Route("/bloque")
 */
class CronogramaController extends BaseController
{
	
	
	/**
     * Creates a new Bloque entity.
     *
     * @Route("/bloque", name="bloque_create")
     * @Method("post")
     */
	public function crearBloqueAction() {
		
	}
	
	/**
     * Modifies a Bloque entity.
     *
     * @Route("/bloque/{id}", name="bloque_update")
     * @Method("post")
     */
	public function crearBloqueAction($id) {
		$entity  = new Bloque();
        $request = $this->getRequest();
		
	}	
	
}