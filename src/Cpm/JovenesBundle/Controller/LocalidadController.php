<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Localidad;
use Cpm\JovenesBundle\Form\LocalidadType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Localidad controller.
 *
 * @Route("/localidad")
 */
class LocalidadController extends BaseController
{
    /**
     * Lists all Localidad entities.
     *
     * @Route("/", name="localidad")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('CpmJovenesBundle:Localidad')->findAllQuery();
        return $this->paginate($entities);
    }

    /**
     * Finds and displays a Localidad entity.
     *
     * @Route("/{id}/show", name="localidad_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Localidad', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            );
    }

    /**
     * Displays a form to create a new Localidad entity.
     *
     * @Route("/new", name="localidad_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Localidad();
        $form   = $this->createForm(new LocalidadType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Localidad entity.
     *
     * @Route("/create", name="localidad_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Localidad:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Localidad();
        $request = $this->getRequest();
        $form    = $this->createForm(new LocalidadType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('localidad'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Localidad entity.
     *
     * @Route("/{id}/edit", name="localidad_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Localidad', $id);
        $editForm = $this->createForm(new LocalidadType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Localidad entity.
     *
     * @Route("/{id}/update", name="localidad_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Persona:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$entity = $this->getEntityForUpdate('CpmJovenesBundle:Localidad', $id, $em);
		$editForm   = $this->createForm(new LocalidadType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('localidad_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Localidad entity.
     *
     * @Route("/{id}/delete", name="localidad_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Localidad', $id, $em);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('localidad'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    
}
