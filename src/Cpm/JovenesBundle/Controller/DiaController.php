<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Form\DiaType;

use Cpm\JovenesBundle\Filter\DiaFilter;
use Cpm\JovenesBundle\Filter\DiaFilterForm;

/**
 * Dia controller.
 *
 * @Route("/dia")
 */
class DiaController extends BaseController
{
    /**
     * Lists all Dia entities.
     *
     * @Route("/", name="dia")
     * @Template()
     */
    public function indexAction()
    {
    	return $this->filterAction(new DiaFilter(), 'dia');
        
    }

    /**
     * Finds and displays a Dia entity.
     *
     * @Route("/{id}/show", name="dia_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Dia', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Dia entity.
     *
     * @Route("/new", name="dia_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Dia();
        $form   = $this->createForm(new DiaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Dia entity.
     *
     * @Route("/create", name="dia_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Dia:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Dia();
        $request = $this->getRequest();
        $form    = $this->createForm(new DiaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dia_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Dia entity.
     *
     * @Route("/{id}/edit", name="dia_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Dia',$id);
        $editForm = $this->createForm(new DiaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Dia entity.
     *
     * @Route("/{id}/update", name="dia_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Dia:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Dia',$id);
        $editForm   = $this->createForm(new DiaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Dia entity.
     *
     * @Route("/{id}/delete", name="dia_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
	        $entity = $this->getEntityForDelete('CpmJovenesBundle:Dia',$id);
			$tanda =$entity->getTanda();
			$tanda->removeDia($entity);
            $em->remove($entity);
            $em->persist($tanda);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dia'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
