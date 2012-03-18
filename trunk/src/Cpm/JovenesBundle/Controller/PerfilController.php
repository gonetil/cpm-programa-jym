<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoType;//
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Plantilla;
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
    * @Route("/inscripcion", name="proyecto_wizzard")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    public function inscripcionAction($id_proyecto = 0) {
    	if ($id_proyecto){
    		$proyecto = $this->getRepository('CpmJovenesBundle:Proyecto')->find($id_proyecto);
    		if (!$proyecto)
    			throw new \Exception("No exite el Proyecto $id_proyecto "); 
    	}else
    		$proyecto = new Proyecto();
    	
    	$form = $this->createForm(new ProyectoType(), $proyecto);
    	$form->remove('coordinador');
    	return array(
                'entity' => $proyecto,
                'coordinador' => $this->getLoggedInUser(),
                'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll(),
                'form'   => $form->createView(),
    			'form_action' => 'proyecto_create_from_wizzard'
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
    	$proyecto->setEstado(Proyecto::__ESTADO_INICIADO);
    	$jym = $this->getJYM();
        $proyecto->setCiclo($jym->getCicloActivo());
        
    	$form = $this->createForm(new ProyectoType(), $proyecto);
    	$form->remove('coordinador');
    	
    	$form->bindRequest($this->getRequest());
    	$this->procesar_colaboradores($proyecto->getColaboradores());
    	 
    	if ($form->isValid()) {
    		$this->doPersist($proyecto);
    		$this->enviarMail($coordinador, Plantilla::ALTA_PROYECTO, array(Plantilla::_PROYECTO => $proyecto));
    		$this->setSuccessMessage("Los datos fueron registrados satifactoriamente");
    		$this->setInfoMessage("Usted se ha inscripto al Programa Jóvenes y Memoria, Convocatoria 2012");
    		
    		return $this->redirect($this->generateUrl('home_usuario'));
    	}
		$this->setErrorMessage("Se produjeron errores al procesar los datos");
    	$distritos = $this->getRepository('CpmJovenesBundle:Distrito')->findAll();
    	$form->remove('coordinador');
    	return    	array(
        	            'entity' => $proyecto,
        				'coordinador' => $coordinador,
        	            'distritos' => $distritos,
        	            'form'   => $form->createView(),
    					'form_action' => 'proyecto_create_from_wizzard'
    	);
    	 
    }
    
    /**
    * Displays a form to edit an existing Proyecto entity.
    *
    * @Route("/{id}/edit", name="proyecto_edit_wizzard")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    public function editAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Proyecto entity.');
    	}
    
    	$editForm = $this->createForm(new ProyectoType(), $entity);
    	
    	$editForm->remove('coordinador');
    	return array(
                'entity'      => $entity,
		    	'coordinador' => $entity->getCoordinador(),
                'form'   => $editForm->createView(),
                'form_action' => 'proyecto_update_wizzard'
          
    	);
    }
    
    
    /**
    * Edits an existing Proyecto entity.
    *
    * @Route("/{id}/update", name="proyecto_update_wizzard")
    * @Method("post")
    * @Template("CpmJovenesBundle:Proyecto:wizzard.html.twig")
    */
    public function updateAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);
    	    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Proyecto entity.');
    	}
        	
    	$entity->setCoordinador($this->getLoggedInUser());
    	 
    	$editForm   = $this->createForm(new ProyectoType(), $entity);
    	$editForm->remove('coordinador');
    	
    	$request = $this->getRequest();
    
    	$editForm->bindRequest($request);
    	$this->procesar_colaboradores($entity->getColaboradores());
    
    	if ($editForm->isValid()) {
    		$em->persist($entity);
    		$em->flush();
    		$this->setSuccessMessage("Los datos fueron actualizados satisfactoriamente");
    		
    		return $this->redirect($this->generateUrl('home_usuario'));
    	}
    	$this->setErrorMessage("Se produjeron errores al procesar los datos");
    	return array(
                'entity'      => $entity,
    			'coordinador' => $this->getLoggedInUser(),
                'form'   => $editForm->createView(),
				'form_action' => 'proyecto_update_wizzard'
    	);
    }
    
    
}
