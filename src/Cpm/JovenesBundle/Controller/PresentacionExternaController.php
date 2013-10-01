<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\PresentacionExterna;
use Cpm\JovenesBundle\Form\PresentacionExternaType;

/**
 * PresentacionExterna controller.
 *
 * @Route("/presentacionexterna")
 */
class PresentacionExternaController extends BaseController
{
    /**
     * Lists all PresentacionExterna entities.
     *
     * @Route("/", name="presentacionexterna")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:PresentacionExterna')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a PresentacionExterna entity.
     *
     * @Route("/{id}/show", name="presentacionexterna_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionExterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionExterna entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
		
		$tandas= $em->getRepository('CpmJovenesBundle:Tanda')->findAll();
        usort($tandas, function($t1,$t2) {  
        							return ( ( $t1->getNumero() < $t2->getNumero() )
        									  ? -1 : 1 );  });
        									  
        return array(
            'entity'      => $entity,
            'tandas' => $tandas,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new PresentacionExterna entity.
     *
     * @Route("/new", name="presentacionexterna_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PresentacionExterna();
        $form   = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new PresentacionExterna entity.
     *
     * @Route("/create", name="presentacionexterna_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:PresentacionExterna:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new PresentacionExterna();
        $request = $this->getRequest();
        $form    = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacionexterna_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing PresentacionExterna entity.
     *
     * @Route("/{id}/edit", name="presentacionexterna_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionExterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionExterna entity.');
        }

        $editForm = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PresentacionExterna entity.
     *
     * @Route("/{id}/update", name="presentacionexterna_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:PresentacionExterna:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:PresentacionExterna')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PresentacionExterna entity.');
        }

        $editForm   = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacionexterna_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a PresentacionExterna entity.
     *
     * @Route("/{id}/delete", name="presentacionexterna_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:PresentacionExterna')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PresentacionExterna entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presentacionexterna'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
