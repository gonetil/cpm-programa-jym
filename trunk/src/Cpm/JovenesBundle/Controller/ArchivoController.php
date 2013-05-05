<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Archivo;
use Cpm\JovenesBundle\Form\ArchivoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Archivo controller.
 *
 * @Route("/archivo")
 */
class ArchivoController extends BaseController
{
    /**
     * Lists all Archivo entities.
     *
     * @Route("/", name="archivo")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('CpmJovenesBundle:Archivo')->findAllQuery();
        return $this->paginate($entities);    
    }

    /**
     * Finds and displays a Archivo entity.
     *
     * @Route("/{id}/show", name="archivo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Archivo entity.
     *
     * @Route("/new", name="archivo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Archivo();
        $form   = $this->createForm(new ArchivoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Archivo entity.
     *
     * @Route("/create", name="archivo_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Archivo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Archivo();
        $request = $this->getRequest();
        $form    = $this->createForm(new ArchivoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
        		
        	
        	
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Archivo entity.
     *
     * @Route("/{id}/edit", name="archivo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $editForm = $this->createForm(new ArchivoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Archivo entity.
     *
     * @Route("/{id}/update", name="archivo_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Archivo:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $editForm   = $this->createForm(new ArchivoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Archivo entity.
     *
     * @Route("/{id}/delete", name="archivo_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Archivo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archivo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('archivo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    

}
