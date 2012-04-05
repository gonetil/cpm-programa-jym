<?php

namespace Cpm\JovenesBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Form\UsuarioType;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler;

use Cpm\JovenesBundle\EntityDummy\UsuarioSearch;
use Cpm\JovenesBundle\Form\UsuarioSearchType;


/**
 * Usuario controller.
 *
 * @Route("/usuario")
 */
class UsuarioController extends BaseController
{
	
	
    /**
     * Lists all Usuario entities.
     *
     * @Route("/", name="usuario")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $searchValues = new UsuarioSearch();
        $searchForm = $this->createForm(new UsuarioSearchType(),$searchValues);
        $request = $this->getRequest();
        if (is_array($request->get("cpm_jovenesbundle_usuariosearchtype"))) {
        	$usuarios = null;
        	
        	$searchForm->bindRequest($request);
        	$repository = $em->getRepository('CpmJovenesBundle:Usuario');
        	if ($searchForm->isValid()) {
        		$usuarios=$repository ->findBySearchCriteriaQuery($searchForm->getData());
        	}
        } else {
        	$usuarios = $em->getRepository('CpmJovenesBundle:Usuario')->findAllQuery();
        }
        
        return $this->paginate($usuarios,array('form'=>$searchForm->createView()));
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{id}/show", name="usuario_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/new", name="usuario_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = $this->getUserManager()->createUser();
        $form   = $this->createForm(new UsuarioType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Usuario entity.
     *
     * @Route("/create", name="usuario_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Usuario:new.html.twig")
     */
    public function createAction()
    {
       $user = $this->getUserManager()->createUser();
       $form = $this->createForm(new UsuarioType(), $user );

	   $form->bindRequest($this->getRequest());
	    
	   $user->setApellido(ucwords(strtolower($user->getApellido())));
	   $user->setNombre(ucwords(strtolower($user->getNombre())));
	   
       if ($form->isValid()) {
       		if ($this->getUserManager()->findUserByEmail($user->getEmail())){
       			$this->setErrorMessage('Ya existe un usuario con email ' . ($user->getEmail()));
       		}else{
	       		if (!$user->getResetPassword()) {
					$user->setPassword('');
		            $this->getMailer()->sendConfirmationEmailMessage($user);
		        } else {
		            $user->setConfirmationToken(null);
		        }
				$this->getUserManager()->updateUser($user);
				$this->setSuccessMessage('Se creo el usuario correctamente.' . ($user->getResetPassword()?' La cuenta está activada.':' Se le envió un correo de activación.'));
					
	      	 	return $this->redirect($this->generateUrl('usuario_show', array('id' => $user->getId())));
       		}
        }

        return array(
            'entity' => $user,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/edit", name="usuario_edit")
     * @Template()
     */
    public function editAction($id)
    {	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $editForm = $this->createForm(new UsuarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/update", name="usuario_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Usuario:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm   = $this->createForm(new UsuarioType(), $user);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $user->setApellido(ucwords(strtolower($user->getApellido())));
        $user->setNombre(ucwords(strtolower($user->getNombre())));
        
        if ($editForm->isValid()) {
        	$otherUser = $this->getUserManager()->findUserByEmail($user->getEmail());
        	if (!$otherUser->equals($user)){
       			$this->setErrorMessage('Ya existe un usuario con email ' . ($user->getEmail()));
       		}else{
       			if ($user->getResetPassword()) {
       				//Se le esta asignando password a un user que nunca estuvo activado. Se lo habilita
					$user->setEnabled(true);
		            $user->setConfirmationToken(null);
		        }
				$this->getUserManager()->updateUser($user);
				$this->setSuccessMessage('Se modificó el usuario correctamente.' . ($user->getResetPassword()?' La cuenta fue activada.':''));
				
	            return $this->redirect($this->generateUrl('usuario_show', array('id' => $id)));
	        }
        }

        return array(
            'entity'      => $user,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}/delete", name="usuario_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

			if ($entity->hasRole('ROLE_ADMIN') || $entity->hasRole('ROLE_SUPER_ADMIN')){
				$this->setErrorMessage("No se puede eliminar al usuario dado que tiene  rol ADMIN. ");
				
			}else{
				$proyectos = $entity->getProyectosCoordinados();
				if (!empty($proyectos))
					$this->setErrorMessage("No se puede eliminar al usuario dado que tiene proyectos asociados como coordinador");
				else{
					$proyectos = $entity->getProyectosColaborados();
					if (!empty($proyectos))
						$this->setErrorMessage("No se puede eliminar al usuario dado que tiene proyectos asociados como colaborador");
					else {
						try{
			            	$em->remove($entity);
							$em->flush();
							$this->setSuccessMessage("Usuario eliminado satisfactoriamente");
			            }catch(\PDOException $e){
			            	$this->setErrorMessage("No se puede eliminar al usuario , revise que no tenga proyectos relacionados");
			            }
	            	}
				}
			}
        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Bloquea o desbloquea al usuario para que pueda loguearse o no
     *
     * @Route("/{id}/toggle_lock", name="usuario_toggle_lock")
     * @Template()
     */
    public function toggleLockAction($id)
    {	
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);
            
            if (!$user->isEnabled()){
            	$this->setErrorMessage('No tiene sentido bloquear un usuario que no esta habilitado para loguearse en el sistema.');
            }else{
            	if (!$user->isLocked() && ($user->hasRole('ROLE_ADMIN') || $user->isSuperAdmin())){
					$this->setErrorMessage("No se puede bloquear un usuario Administrador. ");
            	}else{
		            $user->setLocked(!$user->isLocked());
		            $this->getUserManager()->updateUser($user);
					$this->setSuccessMessage('El usuario '.$user->getEmail().' ha sido '. (($user->isLocked())?'bloqueado.':'desbloqueado.'));
            	}
            }
        }
		return $this->redirect($this->generateUrl('usuario_show', array('id' => $user->getId())));
    }

}
