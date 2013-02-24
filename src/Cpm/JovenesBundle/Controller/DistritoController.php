<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Distrito;
use Cpm\JovenesBundle\Form\DistritoType;

/**
 * Distrito controller.
 *
 * @Route("/distrito")
 */
class DistritoController extends BaseController
{
    /**
     * Lists all Distrito entities.
     *
     * @Route("/", name="distrito")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->getRepository('CpmJovenesBundle:Distrito')->findAllQuery();
        return $this->paginate($entities);
    }

    /**
     * Finds and displays a Distrito entity.
     *
     * @Route("/{id}/show", name="distrito_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Distrito',$id);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Distrito entity.
     *
     * @Route("/new", name="distrito_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Distrito();
        $form   = $this->createForm(new DistritoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Distrito entity.
     *
     * @Route("/create", name="distrito_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Distrito:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Distrito();
        $request = $this->getRequest();
        $form    = $this->createForm(new DistritoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('distrito'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Distrito entity.
     *
     * @Route("/{id}/edit", name="distrito_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Distrito',$id);
        $editForm = $this->createForm(new DistritoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Distrito entity.
     *
     * @Route("/{id}/update", name="distrito_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Persona:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$entity = $this->getEntityForUpdate('CpmJovenesBundle:Distrito',$id, $em);
        $editForm   = $this->createForm(new DistritoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('distrito_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Distrito entity.
     *
     * @Route("/{id}/delete", name="distrito_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Distrito',$id, $em);
        	$em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('distrito'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
