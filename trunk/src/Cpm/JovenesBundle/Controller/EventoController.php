<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Evento;
use Cpm\JovenesBundle\Form\EventoType;


use Cpm\JovenesBundle\Filter\EventoFilter;
use Cpm\JovenesBundle\Filter\EventoFilterForm;
/**
 * Evento controller.
 *
 * @Route("/evento")
 */
class EventoController extends BaseController
{
    /**
     * Lists all Evento entities.
     *
     * @Route("/", name="evento")
     * @Template()
     */
    public function indexAction()
    {
		return $this->filterAction(new EventoFilter(), 'evento');
    }

    /**
     * Finds and displays a Evento entity.
     *
     * @Route("/{id}/show", name="evento_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Evento', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
           	);
    }

    /**
     * Displays a form to create a new Evento entity.
     *
     * @Route("/new", name="evento_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Evento();
        $form   = $this->createForm(new EventoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Evento entity.
     *
     * @Route("/create", name="evento_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Evento:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Evento();
        $request = $this->getRequest();
        $form    = $this->createForm(new EventoType(), $entity);
        $entity->setCiclo($this->getJYM()->getCicloActivo());
        
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
			$this->setSuccessMessage("Evento creado satisfactoriamente");
            return $this->redirect($this->generateUrl('evento_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Evento entity.
     *
     * @Route("/{id}/edit", name="evento_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Evento', $id);
		$editForm = $this->createForm(new EventoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Evento entity.
     *
     * @Route("/{id}/update", name="evento_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Evento:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Evento', $id, $em);
		$editForm   = $this->createForm(new EventoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Evento modificado satisfactoriamente");
            return $this->redirect($this->generateUrl('evento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Evento entity.
     *
     * @Route("/{id}/delete", name="evento_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
			$entity = $this->getEntityForDelete('CpmJovenesBundle:Evento', $id, $em);


			$instancias = $entity->getInstancias();
			
			if (count($instancias) > 0){
				$this->setErrorMessage("No se puede eliminar un evento que posee instancias. Debe eliminar primero las instancias");
			}else{
	            $this->setSuccessMessage("Evento eliminado satisfactoriamente");
	            $em->remove($entity);
	            $em->flush();
			}
        }

        return $this->redirect($this->generateUrl('evento'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
