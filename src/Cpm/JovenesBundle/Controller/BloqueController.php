<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Bloque;
use Cpm\JovenesBundle\Form\BloqueType;

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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Bloque')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Bloque entity.
     *
     * @Route("/{id}/show", name="bloque_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Bloque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bloque entity.');
        }

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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Bloque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bloque entity.');
        }

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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Bloque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bloque entity.');
        }

        $editForm   = $this->createForm(new BloqueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
        	
        	$em->getConnection()->beginTransaction();
	    	try { 
	        	if (!$entity->getTienePresentaciones()) //al no ser un bloque de presentaciones, me aseguro que no tenga ninguna presentacion asignada 
					foreach ( $entity->getPresentaciones() as $index => $presentacion ) {
						$presentacion->setBloque(null);
						$em->persist($presentacion);
		 				$entity->getPresentaciones()->remove($index);
					}	
		        	
	            $em->persist($entity);
	            $em->flush();
				$em->getConnection()->commit();
	    	} catch (\Exception $e) {
	    		$em->getConnection()->rollback();
				$em->close();
	            throw $e;
	    	}
    
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
            $entity = $this->getEntityForRemoval('CpmJovenesBundle:Bloque', $id);
            try{	
	            $em->remove($entity);
	            $em->flush();
	            return $this->answerOk("Bloque eliminado satisfactoriamente");		
	    	} catch (\Exception $e) {
	    		$this->setErrorMessage('Error al inicializar las tandas de Chapadmalal. Mensaje: '.$e);
	            throw $e;
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
