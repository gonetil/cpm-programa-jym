<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\InstanciaEvento;
use Cpm\JovenesBundle\Form\InstanciaEventoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * InstanciaEvento controller.
 *
 * @Route("/instancia")
 */
class InstanciaEventoController extends BaseController
{
    /**
     * Lists all InstanciaEvento entities.
     *
     * @Route("/", name="instancia")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->findAllQuery();

        return $this->paginate($entities);
    }

    /**
     * Finds and displays a InstanciaEvento entity.
     *
     * @Route("/{id}/show", name="instancia_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InstanciaEvento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new InstanciaEvento entity.
     *
     * @Route("/new", name="instancia_new")
     * @Template()
     */
    public function newAction()
    {
    	$entity = new InstanciaEvento();
        
        $eventoid = $this->getRequest()->query->get('evento_id', 0);
    	if ($eventoid)
    		$entity->setEvento($this->getRepository('CpmJovenesBundle:Evento')->find($eventoid));
    		
        $form   = $this->createForm(new InstanciaEventoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new InstanciaEvento entity.
     *
     * @Route("/create", name="instancia_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:InstanciaEvento:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new InstanciaEvento();
        $request = $this->getRequest();
        $form    = $this->createForm(new InstanciaEventoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
			$this->setSuccessMessage("Instancia de evento creada satisfactoriamente");
            return $this->redirect($this->generateUrl('instancia_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing InstanciaEvento entity.
     *
     * @Route("/{id}/edit", name="instancia_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InstanciaEvento entity.');
        }

        $editForm = $this->createForm(new InstanciaEventoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing InstanciaEvento entity.
     *
     * @Route("/{id}/update", name="instancia_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:InstanciaEvento:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InstanciaEvento entity.');
        }

        $editForm   = $this->createForm(new InstanciaEventoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Instancia de evento modificada satisfactoriamente");
            return $this->redirect($this->generateUrl('instancia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a InstanciaEvento entity.
     *
     * @Route("/{id}/delete", name="instancia_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find InstanciaEvento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('instancia'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
    * Busca todas las instancias del evento evento_id
    *
    * @Route("/instancia/find_by_evento_id", name="instancia_find_by_evento_id")
    */
    
    public function findInstanciaDeEvento() 
    { 
    	
    		$evento_id = $this->get('request')->query->get('evento_id');
    		 
    		$em = $this->getDoctrine()->getEntityManager();
    		if ($evento_id > 0)
    			$instancias = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->findByEvento($evento_id);
    	
    		$json = array();
    		foreach ($instancias as $instancia) {
    			$json[] = array("nombre"=>$instancia->__toString(), 
    							"id" => $instancia->getId());
    		}
    		$response = new Response(json_encode($json));
    	
    		$response->headers->set('Content-Type', 'application/json');
    		return $response;
    	
    	    	
    }
}
