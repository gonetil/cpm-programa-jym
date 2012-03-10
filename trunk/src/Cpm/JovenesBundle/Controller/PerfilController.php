<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoWizzardType;
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
    * Muestra informacion de un proyecto del usuario
    * @Route("miproyecto", name="miproyecto_usuario")
    * @Template()
    */
    public function myprojectAction() {
    	$id = $this->getRequest()->get('id'); 
    	$usuario = $this->getLoggedInUser();
    	$proyecto = $this->getRepository("CpmJovenesBundle:Proyecto")->findOneById($id);
    	
    	if ($proyecto && $usuario->equals($proyecto->getCoordinador())) 
    		return array( 'proyecto' => $proyecto );
    	else 
    		return array();
    }
    /**
    * Displays a form to create a new Proyecto entity.
    *
    * @Route("/inscripcion/{id}", name="modificar_inscripcion")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    public function modificarInscripcionAction($id) {
    	return $this->inscripcionAction($id);
    }
    
    /**
    * Displays a form to create a new Proyecto entity.
    *
    * @Route("/inscipcion", name="proyecto_wizzard")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    public function inscripcionAction($id_proyecto = 0) {
    	if ($id_proyecto)
    		$proyecto = $this->getRepository('CpmJovenesBundle:Proyecto')->find($id_proyecto);
    	else
    		$proyecto = new Proyecto();
    	
    	$form   = $this->createForm(new ProyectoWizzardType(), $proyecto);
    
    	return array(
                'entity' => $proyecto,
                'coordinador' => $this->getLoggedInUser(),
                'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll(),
                'form'   => $form->createView()
    	);
    }
    
    /**
     * Guarda un proyecto enviado desde el Wizzard de proyectos
     * @Route("/save_wizzard", name="proyecto_create_from_wizzard")
     * @Method("post")
	 * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
     */
    
    public function createFromWizzardAction() {
    	$proyecto = new Proyecto();
    	$coordinador = $this->getLoggedInUser();
    
    	$proyecto->setCoordinador($coordinador);
    	
    	$form    = $this->createForm(new ProyectoWizzardType(), $proyecto);
    	 
    	$form->bindRequest($this->getRequest());
    	$colaboradores = $proyecto->getColaboradores();
    	foreach ($colaboradores as $colaborador) {
    		if ($c = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($colaborador->getEmail())) //el colaborador ya existia en la bbdd 
    		{ //si el email del colaborador estaba en la BBDD, no creo uno nuevo  
    			$colaboradores->removeElement($colaborador);
    			$colaboradores->add($c);
    		} else 
    		{ //si el colaborador debe ser cargado en la BBDD, le pongo una password vacia
    			$colaborador->setPassword("");
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