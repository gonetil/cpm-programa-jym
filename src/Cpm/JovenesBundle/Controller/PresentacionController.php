<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Presentacion;
use Cpm\JovenesBundle\Form\PresentacionType;

/**
 * Presentacion controller.
 *
 * @Route("/presentacion")
 */
class PresentacionController extends BaseController
{
    /**
     * Lists all Presentacion entities.
     *
     * @Route("/", name="presentacion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Presentacion')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Presentacion entity.
     *
     * @Route("/{id}/show", name="presentacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Presentacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Presentacion entity.');
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
     * Displays a form to create a new Presentacion entity.
     *
     * @Route("/new", name="presentacion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Presentacion();
        $form   = $this->createForm(new PresentacionType($this->getEstadosManager()), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Presentacion entity.
     *
     * @Route("/create", name="presentacion_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Presentacion:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Presentacion();
        $request = $this->getRequest();
        $form    = $this->createForm(new PresentacionType($this->getEstadosManager()), $entity);
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
     * Displays a form to edit an existing Presentacion entity.
     *
     * @Route("/{id}/edit", name="presentacion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Presentacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Presentacion entity.');
        }

        $editForm = $this->createForm(new PresentacionType($this->getEstadosManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Presentacion entity.
     *
     * @Route("/{id}/update", name="presentacion_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Presentacion:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Presentacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Presentacion entity.');
        }

        $editForm   = $this->createForm(new PresentacionType($this->getEstadosManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presentacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Presentacion entity.
     *
     * @Route("/{id}/delete", name="presentacion_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Presentacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Presentacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presentacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
