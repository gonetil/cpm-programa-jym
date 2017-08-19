<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoType;
use Cpm\JovenesBundle\Form\ColaboradoresProyectoType;

use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Form\InvitacionUsuarioType;
use Cpm\JovenesBundle\Form\PresentacionProyectoType;
use Cpm\JovenesBundle\Form\ConfirmacionCamposChapaType;

use Symfony\Component\HttpFoundation\Response;
use Cpm\JovenesBundle\Entity\EstadoProyecto;

//use Cpm\JovenesBundle\EntityDummy\ConfirmarCamposChapa;

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
    	$usuario = $this->getJYM()->getLoggedInUser();
    	$estadosManager = $this->getEstadosManager();
    	if ($message = $this->debeActualizarPerfil($usuario)) {
    		$this->setInfoMessage($message);
    		return $this->redirect($this->generateUrl('fos_user_profile_edit'));
    	}

    	$mis_proyectos = $this->getRepository('CpmJovenesBundle:Proyecto')->findBy(
						    	array('coordinador' => $usuario->getId(),
									  'ciclo' => $this->getJYM()->getCicloActivo()->getId() //agrego el ciclo actual
									  )
		);
        return array (
        			'proyectos' => $mis_proyectos ,
        			'usuario' => $usuario,
        );
    }

    /**
     * Esta función checkea si el usuario debe actualizar su perfil, porque tiene datos incompletos.
     *
     */
    private function debeActualizarPerfil($usuario) {
    		$message = "Por favor, verifique y complete (si corresponde) los siguientes campos: ";
    		$result = false;
    		if (!$usuario->getDomicilio()) {
    			$message .= "Domicilio; ";
    			$result = true;
    		}

    		if (!$usuario->getAniosParticipo()) {
    			$message .= "Años en los que participó; ";
    			$result = true;
    		}


    		if ($result)
	    		return $message;
	    	else
	    		return false;
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
    	if ($id_proyecto)
    		$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id_proyecto);
    	else
    		$proyecto = new Proyecto();

    	$form = $this->createForm(new ProyectoType(), $proyecto);
    	$form->remove('coordinador');
    	$form->remove('requerimientosDeEdicion');
    	$form->remove('produccionFinal');

    	return array(
                'entity' => $proyecto,
                'coordinador' => $this->getJYM()->getLoggedInUser(),
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
    	$coordinador = $this->getJYM()->getLoggedInUser();

    	$proyecto->setCoordinador($coordinador);
    	$cicloActivo = $this->getJYM()->getCicloActivo();
        $proyecto->setCiclo($cicloActivo);

    	$form = $this->createForm(new ProyectoType(), $proyecto);
    	$form->remove('coordinador');
    	$form->remove('requerimientosDeEdicion');

    	$form->bindRequest($this->getRequest());
    	$this->procesar_colaboradores($proyecto->getColaboradores());

    	if ($form->isValid()) {
    		$this->doPersist($proyecto);

    		$this->getMailer()->sendConfirmacionAltaProyecto($proyecto);
    		$this->setSuccessMessage("Los datos fueron registrados satifactoriamente");
    		$this->setInfoMessage("Usted se ha inscripto al Programa Jóvenes y Memoria, Convocatoria ".$cicloActivo->getAnio());

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
    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id);
     	$editForm = $this->createForm(new ProyectoType(), $entity);

    	$editForm->remove('coordinador');
	    $editForm->remove('requerimientosDeEdicion');
	    $editForm->remove('produccionFinal');

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

    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id, $em);

    	$colabs = array(); foreach ($entity->getColaboradores() as $c) {
    		$colabs[] = $c->getEmail();
    	}

    	$entity->setCoordinador($this->getJYM()->getLoggedInUser());

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
    	}else{
    		$this->setErrorMessage("Se produjeron errores al procesar los datos");
    	}
    	return array(
                'entity'      => $entity,
    			'coordinador' => $this->getJYM()->getLoggedInUser(),
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

		$id_ciclo = $this->getRequest()->get('ciclo');

		if (empty($id_ciclo))
	    	$ciclo = $this->getJYM()->getCicloActivo();
		else
			$ciclo = $this->getEntity('CpmJovenesBundle:Ciclo', $id_ciclo);
	 	$usuario = $this->getJYM()->getLoggedInUser();

	 	$cantCorreosPorCiclo = $this->getRepository('CpmJovenesBundle:Correo')->getCantidadCorreosPorCiclo($usuario->getId());

    	$query =$this->getRepository('CpmJovenesBundle:Correo')->findAllQuery($ciclo,$usuario->getId());

    	return $this->paginate($query,array('ciclo'=> $ciclo, 'cantCorreosPorCiclo' => $cantCorreosPorCiclo));

    }

    /**
    * Lista todos los correos del usuarios
    * @Route("/ver_correo", name="ver_correo_usuario")
    * @Method("post")
    * @Template()
    */
    public function verCorreoAction() {
		$usuario =$this->getJYM()->getLoggedInUser();
		$id_correo = $this->getRequest()->get('correo');
		$correo = $this->getEntity("CpmJovenesBundle:Correo", $id_correo);
		if ($correo->getDestinatario()->equals($usuario))
			return array('correo' => $correo);
		else
			return "";
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

        $invitacion = $this->getEntityForUpdate('CpmJovenesBundle:Invitacion', $id);

        if ($invitacion->getInstanciaEvento()->fue() || ($invitacion->estaPendiente() && !$invitacion->estaVigente())){
    		$this->setWarnMessage("El plazo de inscripción para el evento '".$invitacion->getInstanciaEvento()->getTitulo()."'ya cerró.");

            $user =$this->getJYM()->getLoggedInUser();

            if (!$user->isAdmin())
		    	return $this->redirect($this->generateUrl('home'));
        }

		$args = array('invitacion' => $invitacion);
		if ($accion == 'rechazar')
		{
			return $this->render('CpmJovenesBundle:Perfil:rechazarInvitacion.html.twig', $args);
		}
		else
		{
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

        $invitacion = $this->getEntityForUpdate('CpmJovenesBundle:Invitacion', $id);

        $user = $this->getJYM()->getLoggedInUser();

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

      if ( ( $invitacion->getInstanciaEvento()->getEvento()->getSolicitarArchivoAdjunto() ) && ( !$invitacion->getArchivoAdjunto() ) ) {
           $this->setErrorMessage('Error al procesar el archivo adjunto');
           $invitacion->setArchivoAdjunto(null);
           return $this->render('CpmJovenesBundle:Perfil:aceptarInvitacion.html.twig',
             array('invitacion' => $invitacion, 'edit_usuario_form' => $editForm->createView())
           );
      }


	    if (!$editForm->isValid()) {
	            return $this->render('CpmJovenesBundle:Perfil:aceptarInvitacion.html.twig',
	            	array('invitacion' => $invitacion, 'edit_usuario_form' => $editForm->createView())
	            );
			}

		}

		$this->setSuccessMessage("La invitación fue guardada satisfactoriamente.");
      $file = $invitacion->getArchivoAdjunto();
      $uploader = $user->getApellido() . '_' . $user->getNombre();
      $today = new \DateTime();
      $filename = $invitacion->getInstanciaEvento()->getEvento()->getTitulo() .$uploader . $today->format('Ymd_His') . '.' .$file->guessExtension();
      $file->move($this->getUploadDir(), $filename);

      $invitacion->setArchivoAdjunto($filename);
      $this->doPersist($invitacion);

        //Si la invitacion es válida y fue aceptada, mando la notificacion al coordinador por mail
        if ($invitacion->getAceptoInvitacion()){
    		$mailer = $this->getMailer();
    		$mailer->enviarAceptacionInvitacion($invitacion);
    	}

	    return $this->redirect($this->generateUrl('home'));
    }


    /**
    * Muestra el formulario de presentación del proyecto
    *
    * @Route("/{id}/presentar", name="proyecto_presentar")
    * @Template("CpmJovenesBundle:Proyecto:presentar.html.twig")
    */
    public function presentarAction($id)
    {
    	$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto',$id);
    	$proyecto->setDeQueSeTrata("");

    	$form = $this->createForm(new PresentacionProyectoType(), $proyecto);
    	if ($proyecto->getArchivo())
    		$this->setWarnMessage("Atención: este proyecto ya ha sido cargado. Si lo carga nuevamente, la versión anterior será sobreescrita");

    	return array(
                'proyecto'      => $proyecto,
                'form'   => $form->createView(),
                'valid_extensions' => implode(", ",$this->getValidExtensions())
    	);
    }


    /**
    * Procesa el formulario de envio de proyectos
    *
    * @Route("/{id}/recibir_presentacion", name="proyecto_recibir_presentacion")
    * @Template("CpmJovenesBundle:Proyecto:presentar.html.twig")
    */

    public function recibirPresentacion($id) {
    	$em = $this->getDoctrine()->getEntityManager();
		$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto',$id, $em);
    	$form = $this->createForm(new PresentacionProyectoType(), $proyecto);
    	$form->bindRequest($this->getRequest());

    	if ($form->isValid()) {
    			$file = $form['archivo']->getData();

    			$new_filename = $this->subir_archivo($file,$proyecto);
    			$estadosManager = $this->getEstadosManager();
	    		$estadoProyecto = new EstadoProyecto();
	    		$estadoProyecto->setEstado(ESTADO_PRESENTADO);
	    		$estadoProyecto->setArchivo($new_filename);
	    		$estadoProyecto->setUsuario($this->getJYM()->getLoggedInUser());
				$result = $estadosManager->cambiarEstadoAProyecto($proyecto,$estadoProyecto);
	    		if ($result == "success") {
        			$this->setSuccessMessage("El archivo fue cargado satisfactoriamente");
     			}
		        else {
		        	$this->setErrorMessage($result);
		        }

	    		return $this->redirect($this->generateUrl('home_usuario'));
    	}
    	else
    	{
	    	return array(
	    	        'proyecto'      => $proyecto,
	    	        'form'   => $form->createView(),
	                'valid_extensions' => implode(", ",$this->getValidExtensions())
	    	);
    	}
    }


    /**
    * Muestra el formulario de confirmacion de datos antes de Chapa
    *
    * @Route("/{id}/confirmarDatosProyecto", name="proyecto_confirmar_prechapa")
    * @Template("CpmJovenesBundle:Proyecto:confirmarPreChapa.html.twig")
    */
    public function confirmarPreChapaAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
		$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto',$id, $em);
    	$form = $this->createForm(new ConfirmacionCamposChapaType(), $proyecto);

    	return array(
                'proyecto'      => $proyecto,
                'form'   => $form->createView()
    	);
    }


   /**
    * Procesa el formulario de confirmacion de datos del proyectos
    *
    * @Route("/{id}/recibirConfirmacionDatosProyecto", name="proyecto_recibir_confirmacion_prechapa")
    * @Template("CpmJovenesBundle:Proyecto:confirmarPreChapar.html.twig")
    */

    public function recibirConfirmacionPreChapa($id) {
    	$em = $this->getDoctrine()->getEntityManager();
		$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto',$id, $em);
    	$form = $this->createForm(new ConfirmacionCamposChapaType(), $proyecto);
    	$form->bindRequest($this->getRequest());

    	if ($form->isValid()) {
    		$em->persist($proyecto);
    		$em->flush();
        	$this->setSuccessMessage("El archivo fue cargado satisfactoriamente");
     		return $this->redirect($this->generateUrl('home_usuario'));
    	}
    	else
    	{
	    	return array(
	    	        'proyecto'      => $proyecto,
	    	        'form'   => $form->createView()
	    	);
    	}
    }




    /**
    * Envia el archivo del proyecto
    *
    * @Route("/{id}/descargar", name="proyecto_descargar_presentacion")
    *
    */

    public function descargarPresentacionAction($id) {
    	$proyecto = $this->getEntity('CpmJovenesBundle:Proyecto', $id);
		$archivo = $this->getEstadosManager()->getArchivoPresentacion($proyecto);

		if ($archivo) {
		    	$file = $this->getUploadDir()."$id/".$archivo;

		    	$pos = strrpos($file, '.');
		    	$ext =  ($pos!==false)?substr($file, $pos+1) : "";

		    	$human_name = "Proyecto {$proyecto->getId()}.$ext";
		    	$response = new Response();
		    	$response->headers->set('Content-Type', 'application/msword');
		    	$response->headers->set("Content-Disposition", 'Attachment;filename="'.$human_name.'"');
		    	$response->send();
		    	readfile($file);
		    	return $response;
		} else {
			$response = new Response();
			//asi?
			return $response;
		}
    }

    /**
    * Displays a form to edit, add and remove colaboradores from an existing Proyecto entity.
    *
    * @Route("/{id}/edit_colaboradores", name="proyecto_edit_colaboradores")
    * @Template("CpmJovenesBundle:Proyecto:editar_colaboradores.html.twig")
    */
    public function editColaboradoresAction($id)
    {
    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id);
		$editForm = $this->createForm(new ColaboradoresProyectoType(), $entity);

    	return array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'form_action' => 'proyecto_update_colaboradores'
        );
    }

    /**
    * Edits, adds and removes colaboradores from an existing Proyecto entity.
    *
    * @Route("/{id}/update_colaboradores", name="proyecto_update_colaboradores")
    * @Method("post")
    * @Template("CpmJovenesBundle:Proyecto:editar_colaboradores.html.twig")
    */
    public function updateColaboradoresAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id, $em);

    	$colabs = array();
    	foreach ($entity->getColaboradores() as $c) {
    			$colabs[] = $c->getEmail();
    	}
    	$editForm   = $this->createForm(new ColaboradoresProyectoType(), $entity);
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
                'form'   => $editForm->createView(),
				'form_action' => 'proyecto_edit_colaboradores'
    	);
    }





     /**
     * Fetch one eje entity.
     *
     * @Route("/json/fetch_eje", name="eje_fetch")
     * @Method("post")
     */
      public function fetchEjeAction() {
      	return $this->forward("CpmJovenesBundle:Eje:fetchEje");
      }

}
