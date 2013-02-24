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
        $searchValues = new UsuarioSearch();
        $searchForm = $this->createForm(new UsuarioSearchType(),$searchValues);
        $request = $this->getRequest();
        $repository = $this->getRepository('CpmJovenesBundle:Usuario');
        
        if (is_array($request->get("cpm_jovenesbundle_usuariosearchtype"))) 
        {
        	$usuarios = null;
        	$searchForm->bindRequest($request);
        	if ($searchForm->isValid()) {
        		$usuarios=$repository ->findBySearchCriteriaQuery($searchForm->getData(),$this->getJYM()->getCicloActivo());
        	}
        } else {
        	$usuarios = $repository->findAllQuery($this->getJYM()->getCicloActivo());
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
		$entity = $this->getEntity('CpmJovenesBundle:Usuario', $id);
		$ciclos = $em->getRepository('CpmJovenesBundle:Ciclo')->findBy(array(),array('id'=>'desc'));
        $deleteForm = $this->createDeleteForm($id);
        $lockForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        	'lock_form' => $lockForm->createView(), 
        	'ciclos' => $ciclos      );
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
		        	$user->setEnabled(true);
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
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Usuario', $id);
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

		$user = $this->getEntityForUpdate('CpmJovenesBundle:Usuario', $id, $em);
        $editForm   = $this->createForm(new UsuarioType(), $user);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $user->setApellido(ucwords(strtolower($user->getApellido())));
        $user->setNombre(ucwords(strtolower($user->getNombre())));
        
        if ($editForm->isValid()) {
        	$otherUser = $this->getUserManager()->findUserByEmail($user->getEmail());
        	if (($otherUser) && !$otherUser->equals($user)){
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
		$loggedUser = $this->getJYM()->getLoggedInUser();
	
	    $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
			$entity = $this->getEntityForDelete('CpmJovenesBundle:Usuario', $id, $em);
			if ($loggedUser->equals($entity))
				$this->setErrorMessage("No se puede eliminar a si mismo. Pida ayuda.");
			else {
				$proyectos = $entity->getProyectosCoordinados();
				if (count($proyectos) > 0 )
					$this->setErrorMessage("No se puede eliminar al usuario dado que tiene proyectos asociados como coordinador");
				else{
					$proyectos = $entity->getProyectosColaborados();
					if (count($proyectos) > 0)
						$this->setErrorMessage("No se puede eliminar al usuario dado que tiene proyectos asociados como colaborador");
					else {
						try{
			            	$em->remove($entity);
							$em->flush();
							$this->setSuccessMessage("Usuario eliminado satisfactoriamente");
							return $this->redirect($this->generateUrl('usuario'));
			            }catch(\PDOException $e){
			            	$this->setErrorMessage("No se puede eliminar al usuario , revise que no tenga proyectos relacionados");
			            }
	            	}
				}
			}
        }
        return $this->redirect($this->generateUrl('usuario_show', array('id' => $id)));
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
            $user = $this->getEntityForUpdate('CpmJovenesBundle:Usuario', $id);
            
            if (!$user->isEnabled()){
            	$this->setErrorMessage('No tiene sentido bloquear un usuario que no esta habilitado para loguearse en el sistema.');
            }else{
				if ($user->equals($this->getJYM()->getLoggedInUser()))
					$this->setErrorMessage("No se puede bloquear a si mismo. Pida ayuda.");
				else{
		            $user->setLocked(!$user->isLocked());
		            $this->getUserManager()->updateUser($user);
					$this->setSuccessMessage('El usuario '.$user->getEmail().' ha sido '. (($user->isLocked())?'bloqueado.':'desbloqueado.'));
            	}
            }
        }
		return $this->redirect($this->generateUrl('usuario_show', array('id' => $user->getId())));
    }

}
