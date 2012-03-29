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
use Cpm\JovenesBundle\Form\InvitacionUsuarioType;
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
		$this->setInfoMessage(" Por favor, conserve su nombre de usuario y contraseña. Trabajaremos juntos todo el año, y está página será nuestro canal de comunicación principal.");
        return array (
        			'proyectos' => $mis_proyectos ,
        			'usuario' => $usuario,
        );
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
    		
    		$this->getMailer()->sendConfirmacionAltaProyecto($proyecto);
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
    	
    	$colabs = array(); foreach ($entity->getColaboradores() as $c) {
    			$colabs[] = $c->getEmail();
    	}
    	    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Proyecto entity.');
    	}
        	
    	$entity->setCoordinador($this->getLoggedInUser());
    	 
    	$editForm   = $this->createForm(new ProyectoType(), $entity);
    	$editForm->remove('coordinador');
    	$request = $this->getRequest();
    	
    	
    	$editForm->bindRequest($request);
    	
    	$entity->setColaboradores($this->procesar_colaboradores($entity->getColaboradores()));
    	
    	if ($editForm->isValid()) {
    		
    		
    		$em->persist($entity);
    		$em->flush();
    		
    		//los colaboradores eliminados que no estan en otro proyecto seran eliminados
    		$this->eliminar_usuarios_sueltos($entity,$colabs);
    		
    		
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
    
    
    /**
     * Lista todos los correos del usuarios
     * @Route("/mis_correos", name="correos_usuario")
     * @Template()
     */
    	
    public function misCorreosAction() 
    {
    	$usuario = $this->getLoggedInUser();
    	$query = $this->getRepository('CpmJovenesBundle:Correo')->findAllQuery($usuario->getId());
    	return $this->paginate($query);

    }

    /**
    * Lista todos los correos del usuarios
    * @Route("/ver_correo", name="ver_correo_usuario")
    * @Method("post")
    * @Template()
    */
    public function verCorreoAction() {
		$usuario = $usuario = $this->getLoggedInUser();
		$id_correo = $this->getRequest()->get('correo');
		$correo = $this->getRepository("CpmJovenesBundle:Correo")->findOneById($id_correo);
		if ($correo->getDestinatario()->equals($usuario)) 
			return array('correo' => $correo);
		else return "";	 
				
    }

	//////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// EVENTOS DE USUARIOS ////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////
	
    /**
     * Registra la aceptacion o rechazo de la invitacion a un evento por parte de un usuario. 
     *
     * @Route("/invitaciones/{id}/{accion}", name="abrir_invitacion")
     * 
     */
    public function abrirInvitacionAction($id, $accion)
    {
    	if (!in_array($accion, array('rechazar', 'aceptar')))
    		throw $this->createNotFoundException('Accion inválida.');
    	
        $invitacion = $this->getRepository('CpmJovenesBundle:Invitacion')->find($id);
        if (!$invitacion) 
            throw $this->createNotFoundException('No exite la Invitacion solicitada');
        
        $user = $this->getLoggedInUser();
        if (!$user->isAdmin() && !$user->equals($invitacion->getProyecto()->getCoordinador())){
        	throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("Solo el coordinador del proyecto puede aceptar o rechazar sus invitaciones");
        }
        
        if ($invitacion->getInstanciaEvento()->fue() || ($invitacion->estaPendiente() && !$invitacion->estaVigente())){
    		$this->setWarnMessage("El plazo de inscripción para el evento '".$invitacion->getInstanciaEvento()->getTitulo()."'ya cerró.");    	
		    if (!$user->isAdmin())
		    	return $this->redirect($this->generateUrl('home'));
        }
		
					
					
		$args = array('invitacion' => $invitacion);
		if ($accion == 'rechazar'){
			return $this->render('CpmJovenesBundle:Perfil:rechazarInvitacion.html.twig', $args);
		}else{
			$editForm = $this->createForm(new InvitacionUsuarioType(), $invitacion);
			$args['edit_usuario_form']=$editForm->createView();
			return $this->render('CpmJovenesBundle:Perfil:aceptarInvitacion.html.twig', $args);
		}
    }
    
    /**
     * Registra la aceptacion o rechazo de la invitacion a un evento por parte de un usuario. 
     *
     * @Route("/invitaciones/{id}/{accion}/submit", name="abrir_invitacion_submit")
     * 
     */
    public function abrirInvitacionSubmitAction($id, $accion)
    {
        if (!in_array($accion, array('rechazar', 'aceptar')))
    		throw $this->createNotFoundException('Accion inválida.');
    	
        $invitacion = $this->getRepository('CpmJovenesBundle:Invitacion')->find($id);
        if (!$invitacion) 
            throw $this->createNotFoundException('No exite la Invitacion solicitada');
        
        $user = $this->getLoggedInUser();
        if (!$user->isAdmin() && !$user->equals($invitacion->getProyecto()->getCoordinador())){
        	throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("Solo el coordinador del proyecto puede aceptar o rechazar sus invitaciones");
        }
        
        if ($invitacion->getInstanciaEvento()->fue() || ($invitacion->estaPendiente() && !$invitacion->estaVigente())){
    		$this->setWarnMessage("El plazo de inscripción para el evento '".$invitacion->getInstanciaEvento()->getTitulo()."'ya cerró.");    	
		    if (!$user->isAdmin())
		    	return $this->redirect($this->generateUrl('home'));
        }
        
		if ($accion == 'rechazar'){
			$invitacion->setAceptoInvitacion(false);
		}else{
			$invitacion->setAceptoInvitacion(true);
			$editForm = $this->createForm(new InvitacionUsuarioType(), $invitacion);
			
			$request = $this->getRequest();
	        $editForm->bindRequest($request);
	
	        if (!$editForm->isValid()) {
	            return $this->render('CpmJovenesBundle:Perfil:aceptarInvitacion.html.twig', 
	            	array('invitacion' => $invitacion, 'edit_usuario_form' => $editForm->createView())
	            );
	            //FIXME, redireccionar al action del evento
			}
        }
		$this->setSuccessMessage("La invitación fue guardada satisfactoriamente.");
        $this->doPersist($invitacion);
	    
	    return $this->redirect($this->generateUrl('home'));

    }
}
