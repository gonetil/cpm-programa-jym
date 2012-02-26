<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Produccion;
use Cpm\JovenesBundle\Form\ProduccionType;

/**
 * Produccion controller.
 *
 * @Route("/produccion")
 */
class ProduccionController extends Controller
{
    /**
     * Lists all Produccion entities.
     *
     * @Route("/", name="produccion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Produccion')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Produccion entity.
     *
     * @Route("/{id}/show", name="produccion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Produccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Produccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Produccion entity.
     *
     * @Route("/new", name="produccion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Produccion();
        $form   = $this->createForm(new ProduccionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Produccion entity.
     *
     * @Route("/create", name="produccion_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Produccion:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Produccion();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProduccionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('produccion_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Produccion entity.
     *
     * @Route("/{id}/edit", name="produccion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Produccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Produccion entity.');
        }

        $editForm = $this->createForm(new ProduccionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Produccion entity.
     *
     * @Route("/{id}/update", name="produccion_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Produccion:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Produccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Produccion entity.');
        }

        $editForm   = $this->createForm(new ProduccionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('produccion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Produccion entity.
     *
     * @Route("/{id}/delete", name="produccion_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Produccion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Produccion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('produccion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
