<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\AuditorioDia;
use Cpm\JovenesBundle\Form\AuditorioDiaType;

use Cpm\JovenesBundle\Filter\AuditorioDiaFilter;
use Cpm\JovenesBundle\Filter\AuditorioDiaFilterForm;

/**
 * AuditorioDia controller.
 *
 * @Route("/auditoriodia")
 */
class AuditorioDiaController extends BaseController
{
    /**
     * Lists all AuditorioDia entities.
     *
     * @Route("/", name="auditoriodia")
     * @Template()
     */
    public function indexAction()
    {
       return $this->filterAction(new AuditorioDiaFilter(), 'auditoriodia');
       
    }

    /**
     * Finds and displays a AuditorioDia entity.
     *
     * @Route("/{id}/show", name="auditoriodia_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:AuditorioDia', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new AuditorioDia entity.
     *
     * @Route("/new", name="auditoriodia_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AuditorioDia();
        $form   = $this->createForm(new AuditorioDiaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new AuditorioDia entity.
     *
     * @Route("/create", name="auditoriodia_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:AuditorioDia:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new AuditorioDia();
        $request = $this->getRequest();
        $form    = $this->createForm(new AuditorioDiaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('auditoriodia_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing AuditorioDia entity.
     *
     * @Route("/{id}/edit", name="auditoriodia_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:AuditorioDia',$id);
        $editForm = $this->createForm(new AuditorioDiaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing AuditorioDia entity.
     *
     * @Route("/{id}/update", name="auditoriodia_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:AuditorioDia:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getEntityForUpdate('CpmJovenesBundle:AuditorioDia',$id);
        $editForm   = $this->createForm(new AuditorioDiaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('auditoriodia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a AuditorioDia entity.
     *
     * @Route("/{id}/delete", name="auditoriodia_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
    	    $entity = $this->getEntityForDelete('CpmJovenesBundle:AuditorioDia',$id);
	
			$dia =$entity->getDia();
			$dia->removeAuditorioDia($entity);
            $em->remove($entity);
            $em->persist($dia);
            
            
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('auditoriodia'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
