<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Eje;
use Cpm\JovenesBundle\Form\EjeType;
use Symfony\Component\HttpFoundation\Response;
use Cpm\JovenesBundle\Controller\BaseController;

/**
 * Eje controller.
 *
 * @Route("/eje")
 */
class EjeController extends BaseController
{
    /**
     * Lists all Eje entities.
     *
     * @Route("/", name="eje")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->getRepository('CpmJovenesBundle:Eje')->findAll();
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Eje entity.
     *
     * @Route("/{id}/show", name="eje_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Eje', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Eje entity.
     *
     * @Route("/new", name="eje_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Eje();
        $form   = $this->createForm(new EjeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Eje entity.
     *
     * @Route("/create", name="eje_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Eje:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Eje();
        $request = $this->getRequest();
        $form    = $this->createForm(new EjeType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eje_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Eje entity.
     *
     * @Route("/{id}/edit", name="eje_edit")
     * @Template()
     */
    public function editAction($id)
    {
		$entity = $this->getEntityForUpdate('CpmJovenesBundle:Eje', $id);
        $editForm = $this->createForm(new EjeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Eje entity.
     *
     * @Route("/{id}/update", name="eje_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Eje:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$entity = $this->getEntityForUpdate('CpmJovenesBundle:Eje', $id, $em);

        $editForm   = $this->createForm(new EjeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eje_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Eje entity.
     *
     * @Route("/{id}/delete", name="eje_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Eje', $id, $em);
			$em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eje'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    
    
     /**
     * Fetch one eje entity.
     *
     * @Route("/fetch_eje")
     * @Method("post")
     */
      public function fetchEjeAction() {
    	$eje_id = $this->getRequest()->get('eje_id');
 		$eje = $this->getRepository('CpmJovenesBundle:Eje')->find($eje_id);

 		$json = array();
		if ($eje) {
            $temas = $eje->getEjesTematicos()->toArray();
            $temas = array_filter($temas, function($tema) { return ( $tema->getAnulado()=== FALSE); } );
            $json['descripcion'] = $eje->getDescripcion();
			$json['id'] = $eje->getId();
			$json['ejesTematicos'] = array_map(  function($tema) { return array('id'=>$tema->getId(),'nombre'=>$tema->getNombre() ); }, 
												$temas
												);		
		}
		
		$response = new Response(json_encode($json));
    	
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;    
    }
}
