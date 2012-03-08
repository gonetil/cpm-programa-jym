<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Tema;
use Cpm\JovenesBundle\Form\TemaType;

/**
 * Tema controller.
 *
 * @Route("/tema")
 */
class TemaController extends BaseController
{
    /**
     * Lists all Tema entities.
     *
     * @Route("/", name="tema")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Tema')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Tema entity.
     *
     * @Route("/{id}/show", name="tema_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tema entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tema entity.
     *
     * @Route("/new", name="tema_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tema();
        $form   = $this->createForm(new TemaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tema entity.
     *
     * @Route("/create", name="tema_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tema:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Tema();
        $request = $this->getRequest();
        $form    = $this->createForm(new TemaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tema_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tema entity.
     *
     * @Route("/{id}/edit", name="tema_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tema entity.');
        }

        $editForm = $this->createForm(new TemaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tema entity.
     *
     * @Route("/{id}/update", name="tema_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tema:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tema entity.');
        }

        $editForm   = $this->createForm(new TemaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tema_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tema entity.
     *
     * @Route("/{id}/delete", name="tema_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Tema')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tema entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tema'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
