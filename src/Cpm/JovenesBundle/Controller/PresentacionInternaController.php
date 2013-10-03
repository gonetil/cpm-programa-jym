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
class PresentacionInternaController extends BaseController
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
            return $this->redirect($this->generateUrl('presentacion_show', array('id' => $entity->getId())));
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
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionInterna', $id);
        $editForm = $this->createForm(new PresentacionInternaType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionInterna', $id);
        $editForm   = $this->createForm(new PresentacionInternaType(), $entity);

        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
        	$em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacion_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
}
