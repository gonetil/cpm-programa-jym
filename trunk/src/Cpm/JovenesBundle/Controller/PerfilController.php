<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoType;
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Entity\Usuario;

/**
 * Perfil controller.
 *
 */
class PerfilController extends BaseController
{
    /**
     * Lists all Proyecto entities.
     * @Route("/home", name="home_usuario")
     * @Template()
     */
    public function indexAction()
    {
    	$usuario = $this->getLoggedInUser();
    	$mis_proyectos = $this->getRepository('CpmJovenesBundle:Proyecto')->findBy(
						    	array('coordinador' => $usuario->getId())
				    	);
    	
        return array (
        			'proyectos' => $mis_proyectos ,
        			'usuario' => $usuario
        		);
    }

    /**
    * Displays a form to create a new Proyecto entity.
    *
    * @Route("/wizzard", name="proyecto_wizzard")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    
    public function wizzardAction() {
    	$proyecto = new Proyecto();
    	$coordinador = $this->getLoggedInUser();
    	if (!$coordinador) { 
    		$this->getSession()->setFlash('notice', 'Su sesión ha expirado');
    		return $this->redirect($this->generateUrl("home"));
    	}
    	
    	$form   = $this->createForm(new ProyectoType(), $proyecto);
    
    	return array(
                'entity' => $proyecto,
                'coordinador' => $coordinador,
                'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll(),
                'form'   => $form->createView()
    	);
    }
    
    /**
     * Guarda un proyecto enviado desde el Wizzard de proyectos
     *
     * @Route("/save_wizzard", name="proyecto_create_from_wizzard")
     * @Method("post")
     *
     */
    
    public function createFromWizzardAction() {
    	$proyecto = new Proyecto();
    	$coordinador = $this->getLoggedInUser();
    
    	$proyecto->setCoordinador($coordinador);
    	
    	$form    = $this->createForm(new ProyectoType(), $proyecto);
    	 
    	$form->bindRequest($this->getRequest());
    	$colaboradores = $proyecto->getColaboradores();
    	foreach ($colaboradores as $colaborador) {
    		if ($c = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($colaborador->getEmail())) //el colaborador ya existia en la bbdd 
    		{ 
    			$colaboradores->removeElement($colaborador);
    			$colaboradores->add($c);
    		}
    	}
    	 
    	if ($form->isValid()) {
    		$this->doPersist($proyecto);
    		
    		return $this->forward('CpmJovenesBundle:Perfil:index');
    	}

    	$distritos = $this->getRepository('CpmJovenesBundle:Distrito')->findAll();
    	 
    	return    	array(
        	            'entity' => $proyecto,
        				'coordinador' => $coordinador,
        	            'distritos' => $distritos,
        	            'form'   => $form->createView()
    	);
    	 
    }
}
