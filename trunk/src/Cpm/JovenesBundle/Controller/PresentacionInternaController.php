<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\PresentacionInterna;
use Cpm\JovenesBundle\Form\PresentacionInternaType;

/**
 * PresentacionInterna controller.
 *
 * @Route("/presentacioninterna")
 */
class PresentacionInternaController extends Controller
{
    /**
     * Lists all PresentacionInterna entities.
     *
     * @Route("/", name="presentacioninterna")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:PresentacionInterna')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a PresentacionInterna entity.
     *
     * @Route("/{id}/show", name="presentacioninterna_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionInterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionInterna entity.');
        }

        $tandas= $em->getRepository('CpmJovenesBundle:Tanda')->findAll();
        usort($tandas, function($t1,$t2) {  
        							return ( ( $t1->getNumero() < $t2->getNumero() )
        									  ? -1 : 1 );  });
        									  
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'tandas' => $tandas,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new PresentacionInterna entity.
     *
     * @Route("/new", name="presentacioninterna_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PresentacionInterna();
        $form   = $this->createForm(new PresentacionInternaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new PresentacionInterna entity.
     *
     * @Route("/create", name="presentacioninterna_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:PresentacionInterna:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new PresentacionInterna();
        $request = $this->getRequest();
        $form    = $this->createForm(new PresentacionInternaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacioninterna_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing PresentacionInterna entity.
     *
     * @Route("/{id}/edit", name="presentacioninterna_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionInterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionInterna entity.');
        }

        $editForm = $this->createForm(new PresentacionInternaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PresentacionInterna entity.
     *
     * @Route("/{id}/update", name="presentacioninterna_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:PresentacionInterna:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionInterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionInterna entity.');
        }

        $editForm   = $this->createForm(new PresentacionInternaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacioninterna_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a PresentacionInterna entity.
     *
     * @Route("/{id}/delete", name="presentacioninterna_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:PresentacionInterna')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PresentacionInterna entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presentacioninterna'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
