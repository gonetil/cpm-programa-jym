<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Form\EscuelaType;

/**
 * Escuela controller.
 *
 * @Route("/escuela")
 */
class EscuelaController extends BaseController
{
    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="escuela")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Escuela')->findAllQuery();

        return $this->paginate($entities);
    }

    /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/{id}/show", name="escuela_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Escuela no encontrada');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to edit an existing Escuela entity.
     *
     * @Route("/{id}/edit", name="escuela_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Escuela no encontrada');
        }

        $editForm = $this->createForm(new EscuelaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Escuela entity.
     *
     * @Route("/{id}/update", name="escuela_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Escuela:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Escuela no encontrada');
        }

        $editForm   = $this->createForm(new EscuelaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Escuela modificada satisfactoriamente");
            return $this->redirect($this->generateUrl('escuela_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Escuela entity.
     *
     * @Route("/{id}/delete", name="escuela_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Escuela')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Escuela no encontrada');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('escuela'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
}