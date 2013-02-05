<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\TipoEscuela;
use Cpm\JovenesBundle\Form\TipoEscuelaType;

/**
 * TipoEscuela controller.
 *
 * @Route("/tipo_escuela")
 */
class TipoEscuelaController extends BaseController
{
    /**
     * Lists all TipoEscuela entities.
     *
     * @Route("/", name="tipo_escuela")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:TipoEscuela')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a TipoEscuela entity.
     *
     * @Route("/{id}/show", name="tipo_escuela_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoEscuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEscuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new TipoEscuela entity.
     *
     * @Route("/new", name="tipo_escuela_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoEscuela();
        $form   = $this->createForm(new TipoEscuelaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new TipoEscuela entity.
     *
     * @Route("/create", name="tipo_escuela_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:TipoEscuela:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TipoEscuela();
        $request = $this->getRequest();
        $form    = $this->createForm(new TipoEscuelaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_escuela_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing TipoEscuela entity.
     *
     * @Route("/{id}/edit", name="tipo_escuela_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoEscuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEscuela entity.');
        }

        $editForm = $this->createForm(new TipoEscuelaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TipoEscuela entity.
     *
     * @Route("/{id}/update", name="tipo_escuela_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:TipoEscuela:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:TipoEscuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEscuela entity.');
        }

        $editForm   = $this->createForm(new TipoEscuelaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_escuela_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TipoEscuela entity.
     *
     * @Route("/{id}/delete", name="tipo_escuela_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:TipoEscuela')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoEscuela entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipo_escuela'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
