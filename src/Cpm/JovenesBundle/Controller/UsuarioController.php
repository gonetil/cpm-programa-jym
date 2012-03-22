<?php

namespace Cpm\JovenesBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Form\UsuarioType;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler;

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

        $query = $em->getRepository('CpmJovenesBundle:Usuario')->findAllQuery();
        
        return $this->paginate($query);
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
    	$confirmationEnabled = false; //TODO recuperar confirmationEnabled de algun lado
        
        $user = $this->getUserManager()->createUser();
        $form = $this->createForm(new UsuarioType(), $user );

	    $form->bindRequest($this->getRequest());
	    
	    $user->setApellido(ucwords(strtolower($user->getApellido())));
	    $user->setNombre(ucwords(strtolower($user->getNombre())));
	    
	     
       if ($form->isValid()) {
       		if ($confirmationEnabled) {
				$user->setEnabled(false);
				$user->setPassword('');
	            $this->getMailer()->sendConfirmationEmailMessage($user);
	        } else {
	            $user->setConfirmationToken(null);
	            $user->setEnabled(true);
	        }
			$this->getUserManager()->updateUser($user);
			$this->setSuccessMessage('Se creo el usuario correctamente.' . ($confirmationEnabled?'se le envió un correo de confirmación.':''));
				
      	 	return $this->redirect($this->generateUrl('usuario_show', array('id' => $user->getId())));
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

        $entity = $em->getRepository('CpmJovenesBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm   = $this->createForm(new UsuarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $entity->setApellido(ucwords(strtolower($entity->getApellido())));
        $entity->setNombre(ucwords(strtolower($entity->getNombre())));
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Usuario modificado satisfactoriamente");
            return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
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
}
