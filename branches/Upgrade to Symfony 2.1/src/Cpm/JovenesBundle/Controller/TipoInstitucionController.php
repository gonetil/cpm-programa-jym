<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\TipoInstitucion;
use Cpm\JovenesBundle\Form\TipoInstitucionType;

/**
 * TipoInstitucion controller.
 *
 * @Route("/tipo_institucion")
 */
class TipoInstitucionController extends BaseController
{
    /**
     * Lists all TipoInstitucion entities.
     *
     * @Route("/", name="tipo_institucion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:TipoInstitucion')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a TipoInstitucion entity.
     *
     * @Route("/{id}/show", name="tipo_institucion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoInstitucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new TipoInstitucion entity.
     *
     * @Route("/new", name="tipo_institucion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoInstitucion();
        $form   = $this->createForm(new TipoInstitucionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new TipoInstitucion entity.
     *
     * @Route("/create", name="tipo_institucion_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:TipoInstitucion:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TipoInstitucion();
        $request = $this->getRequest();
        $form    = $this->createForm(new TipoInstitucionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_institucion_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing TipoInstitucion entity.
     *
     * @Route("/{id}/edit", name="tipo_institucion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoInstitucion entity.');
        }

        $editForm = $this->createForm(new TipoInstitucionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TipoInstitucion entity.
     *
     * @Route("/{id}/update", name="tipo_institucion_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:TipoInstitucion:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoInstitucion entity.');
        }

        $editForm   = $this->createForm(new TipoInstitucionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_institucion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TipoInstitucion entity.
     *
     * @Route("/{id}/delete", name="tipo_institucion_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:TipoInstitucion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoInstitucion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipo_institucion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
