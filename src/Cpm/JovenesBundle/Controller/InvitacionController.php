<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Invitacion;
use Cpm\JovenesBundle\Form\InvitacionType;
use Cpm\JovenesBundle\EntityDummy\InvitacionBatch;
use Cpm\JovenesBundle\Form\InvitacionBatchType;
use Cpm\JovenesBundle\Filter\InvitacionFilter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invitacion controller.
 *
 * @Route("/invitaciones")
 */
class InvitacionController extends BaseController
{
    /**
     * Lists all Invitacion entities.
     *
     * @Route("/", name="invitaciones")
     * @Template()
     */
    public function indexAction()
    {
    	return $this->filterAction(new InvitacionFilter(), 'invitacion');


    }
    
    /**
     * Muetsra un form de invitacion de proyectos a una instancia de un evento
     *
     * @Template()
     */
    public function invitarProyectosBatchAction($entitiesQuery)
    {
    	$entities=$entitiesQuery->getResult();
        $editForm = $this->createForm(new InvitacionBatchType(), new InvitacionBatch());
        
        return array(
            'form'   => $editForm->createView(),
            'proyectos' => $entities
        );
    }
    
    /**
     * Invitacion una serie de proyectos a una instancia de un evento
     *
     * @Route("/invitar_proyectos_submit", name="invitar_proyectos_submit")
     * @Method("post")
     * @Template("CpmJovenesBundle:Invitacion:invitarProyectosBatch.html.twig")
     */
    public function invitarProyectosBatchSubmitAction()
    {

        $invitacionBatch = new InvitacionBatch();
		$editForm = $this->createForm(new InvitacionBatchType(), $invitacionBatch);
        $request = $this->getRequest();
        $editForm->bindRequest($request);
		$ev_mgr= $this->getEventosManager();
        if ($editForm->isValid()) {
        	try{
        		$repetidas = $ev_mgr->invitarProyectos($this->getLoggedInUser(), $invitacionBatch);
        		if (count($repetidas) > 0) { 
        			$coordinadores = "";
        			foreach ( $repetidas as $proyecto) {
        				$coordinadores .= $proyecto->getCoordinador()." ; ";
        			}
        		$this->setInfoMessage("Lista de invitaciones repetidas (no fueron reenviadas): ".$coordinadores);	
        		}
        		return $this->redirect($this->generateUrl('instancia_show', array('id' => $invitacionBatch->getInstancia()->getId())));
        	}catch(InvalidTemplateException $e){
					$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
			}catch(MailCannotBeSentException $e){
				$this->setErrorMessage('No se pudieron enviar las invitaciones por correo. Verifique que los datos ingresados sean válidos');
			}	
		
        }

        return array(
            'invitacionBatch'      => $invitacionBatch,
            'form'   => $editForm->createView(),
        );
    }
    
        

    /**
     * Finds and displays a Invitacion entity.
     *
     * @Route("/{id}/show", name="invitaciones_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invitacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),);
    }

  
    /**
     * Displays a form to edit an existing Invitacion entity.
     *
     * @Route("/{id}/edit", name="invitaciones_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invitacion entity.');
        }

        $editForm = $this->createForm(new InvitacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Invitacion entity.
     *
     * @Route("/{id}/update", name="invitaciones_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Invitacion:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invitacion entity.');
        }

        $editForm   = $this->createForm(new InvitacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('invitaciones_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Invitacion entity.
     *
     * @Route("/{id}/delete", name="invitaciones_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Invitacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('invitaciones'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
    * Actualiza el campo asistencia de una entidad
    *
    * @Route("/{id}/set_single_asistencia", name="invitaciones_set_single_asistencia")
    * @Method("get")
    */
    public function set_single_asistencia($id) {
    	    	
    	$asistio = ($this->get('request')->query->get('asistencia') == 'true');

    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);
    	
    	if ($entity) {
    		$entity->setAsistio($asistio);
    		$em->persist($entity);
    		$em->flush();
    		return new Response("success");
    	}
    	else new Response("error");
    }
    
    
    /**
     * Reenvia una invitación previamente existente
     * @Route("/{id}/reenviar_invitacion" , name="invitaciones_reenviar_una")
     * @Method("get")
     */
    public function reenviarInvitacion($id) { 
    	
    	$em = $this->getDoctrine()->getEntityManager();
        $invitacion = $em->getRepository('CpmJovenesBundle:Invitacion')->find($id);

        if (!$invitacion) {
            throw $this->createNotFoundException('No se encontró la invitación '.$id);
            return new Response("error");
        }
		$eventosManager = $this->getEventosManager();    	
    	$eventosManager->enviarInvitacionAProyecto($invitacion, false,false); //no se envía cc a los colaboradores ni a la escuela 
    	return new Response("success");
    }
}
