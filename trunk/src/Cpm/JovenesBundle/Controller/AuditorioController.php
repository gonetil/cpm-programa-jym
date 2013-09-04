<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Auditorio;
use Cpm\JovenesBundle\Form\AuditorioType;

/**
 * Auditorio controller.
 *
 * @Route("/auditorio")
 */
class AuditorioController extends Controller
{
    /**
     * Lists all Auditorio entities.
     *
     * @Route("/", name="auditorio")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Auditorio')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Auditorio entity.
     *
     * @Route("/{id}/show", name="auditorio_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Auditorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Auditorio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Auditorio entity.
     *
     * @Route("/new", name="auditorio_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Auditorio();
        $form   = $this->createForm(new AuditorioType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Auditorio entity.
     *
     * @Route("/create", name="auditorio_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Auditorio:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Auditorio();
        $request = $this->getRequest();
        $form    = $this->createForm(new AuditorioType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('auditorio_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Auditorio entity.
     *
     * @Route("/{id}/edit", name="auditorio_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Auditorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Auditorio entity.');
        }

        $editForm = $this->createForm(new AuditorioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Auditorio entity.
     *
     * @Route("/{id}/update", name="auditorio_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Auditorio:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Auditorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Auditorio entity.');
        }

        $editForm   = $this->createForm(new AuditorioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('auditorio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Auditorio entity.
     *
     * @Route("/{id}/delete", name="auditorio_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Auditorio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Auditorio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('auditorio'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
