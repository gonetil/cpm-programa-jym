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

            return $this->redirect($this->generateUrl('presentacion_show', array('id' => $entity->getId())));
            
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

        $entity = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionExterna',$id);

        $editForm = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
        
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:PresentacionExterna', $id);
        $editForm   = $this->createForm(new PresentacionExternaType($this->getEstadosManager()), $entity);
        
        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
        	$em = $this->getDoctrine()->getEntityManager();
        	$em->persist($entity);
            $em->flush();
			$this->setSuccessMessage("Se guardo la presentacion satisfactoriamente");
		    return $this->redirect($this->generateUrl('presentacion_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

}
