<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Bloque;
use Cpm\JovenesBundle\Form\BloqueType;


use Cpm\JovenesBundle\Filter\BloqueFilter;
use Cpm\JovenesBundle\Filter\BloqueFilterForm;


/**
 * Bloque controller.
 *
 * @Route("/bloque")
 */
class BloqueController extends BaseController
{
    /**
     * Lists all Bloque entities.
     *
     * @Route("/", name="bloque")
     * @Template()
     */
    public function indexAction()
    {
    	
       return $this->filterAction(new BloqueFilter(), 'bloque');
    }

    /**
     * Finds and displays a Bloque entity.
     *
     * @Route("/{id}/show", name="bloque_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Bloque', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Bloque entity.
     *
     * @Route("/new", name="bloque_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Bloque();
        $form   = $this->createForm(new BloqueType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Bloque entity.
     *
     * @Route("/create", name="bloque_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Bloque:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Bloque();
        $request = $this->getRequest();
        $form    = $this->createForm(new BloqueType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bloque_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Bloque entity.
     *
     * @Route("/{id}/edit", name="bloque_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Bloque', $id);

        $editForm = $this->createForm(new BloqueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Bloque entity.
     *
     * @Route("/{id}/update", name="bloque_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Bloque:edit.html.twig")
     */
    public function updateAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Bloque', $id);
        $editForm   = $this->createForm(new BloqueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();
	    $editForm->bindRequest($request);

        if ($editForm->isValid()) {
	        $em = $this->getDoctrine()->getEntityManager();
	    	
	    	if (!$entity->getTienePresentaciones()){ 
	        	//al no ser un bloque de presentaciones, me aseguro que no tenga ninguna presentacion asignada
	        	$presentaciones = $entity->getPresentaciones()->toArray(); 
				foreach ( $presentaciones as $presentacion ) {
						$entity->removePresentacion($presentacion);
						$em->persist($presentacion);
				}	
	        }
	        $em->persist($entity);
	        $em->flush();
			$this->setSuccessMessage("Bloque modificado satisfactoriamente");
       
            return $this->redirect($this->generateUrl('bloque_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Bloque entity.
     *
     * @Route("/{id}/delete", name="bloque_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Bloque', $id);
            try{	
            	$auditorioDia =$entity->getAuditorioDia();
				$auditorioDia->removeBloque($entity);
	            $em->remove($entity);
	            $em->persist($auditorioDia);
            
	            $em->remove($entity);
	            $em->flush();
	            $this->setSuccessMessage("Bloque eliminado satisfactoriamente");
	    	} catch (\Exception $e) {
	    		$this->setErrorMessage('Se produjo un error al tratar de eliminar el bloque . Mensaje: '.$e);
	    	}

        }
		return $this->redirect($this->generateUrl('bloque'));
        
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
