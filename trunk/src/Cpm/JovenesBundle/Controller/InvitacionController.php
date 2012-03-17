<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Invitacion;
use Cpm\JovenesBundle\Form\InvitacionType;

/**
 * Invitacion controller.
 *
 * @Route("/invitaciones")
 */
class InvitacionController extends Controller
{
    /**
     * Lists all Invitacion entities.
     *
     * @Route("/", name="invitaciones")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Invitacion')->findAll();

        return array('entities' => $entities);
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
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Invitacion entity.
     *
     * @Route("/new", name="invitaciones_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Invitacion();
        $form   = $this->createForm(new InvitacionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Invitacion entity.
     *
     * @Route("/create", name="invitaciones_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Invitacion:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Invitacion();
        $request = $this->getRequest();
        $form    = $this->createForm(new InvitacionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('invitaciones_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
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
}
