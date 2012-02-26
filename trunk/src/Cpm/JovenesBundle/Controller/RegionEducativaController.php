<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\RegionEducativa;
use Cpm\JovenesBundle\Form\RegionEducativaType;

/**
 * RegionEducativa controller.
 *
 * @Route("/region")
 */
class RegionEducativaController extends Controller
{
    /**
     * Lists all RegionEducativa entities.
     *
     * @Route("/", name="region")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:RegionEducativa')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a RegionEducativa entity.
     *
     * @Route("/{id}/show", name="region_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:RegionEducativa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegionEducativa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new RegionEducativa entity.
     *
     * @Route("/new", name="region_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new RegionEducativa();
        $form   = $this->createForm(new RegionEducativaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new RegionEducativa entity.
     *
     * @Route("/create", name="region_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:RegionEducativa:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new RegionEducativa();
        $request = $this->getRequest();
        $form    = $this->createForm(new RegionEducativaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('region'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing RegionEducativa entity.
     *
     * @Route("/{id}/edit", name="region_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:RegionEducativa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegionEducativa entity.');
        }

        $editForm = $this->createForm(new RegionEducativaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing RegionEducativa entity.
     *
     * @Route("/{id}/update", name="region_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Persona:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:RegionEducativa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegionEducativa entity.');
        }

        $editForm   = $this->createForm(new RegionEducativaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('region_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a RegionEducativa entity.
     *
     * @Route("/{id}/delete", name="region_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:RegionEducativa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RegionEducativa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('region'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
