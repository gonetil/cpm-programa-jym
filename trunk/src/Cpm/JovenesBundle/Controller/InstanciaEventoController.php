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
		$reinvitarForm = $this->createReinvitarForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        	'reinvitar_form' =>  $reinvitarForm->createView()
              );
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
			
            if (count($entity->getInvitaciones()) > 0) {
                $this->setErrorMessage("No se puede eliminar la instancia de evento porque posee invitaciones");
                return $this->redirect($this->generateUrl('instancia_show', array('id'=>$id)));
            }else{
            	$em->remove($entity);
            	$em->flush();
            	$this->setSuccessMessage("Se elimino la instancia del evento satisfactoriamente");
            }

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

    /**
    *  @Route("/{id}/export_to_excel", name="instancia_export_to_excel")
    * @Method("get")
    * @Template("CpmJovenesBundle:InstanciaEvento:export_excel.x,s.twig")
    */
    
    public function exportToExcelAction($id) {
    	$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontró la Instancia de Evento');
        }
		$filename = "{$entity->getEvento()->getTitulo()} ({$entity->getFechaInicio()->format('d-m H:i')} {$entity->getFechaFin()->format('d-m H:i')})";
		
		
        $response = $this->render('CpmJovenesBundle:InstanciaEvento:export_excel.xls.twig',array('entity' => $entity));
        $response->headers->set('Content-Type', 'application/msexcel');
        $response->headers->set("Content-Disposition", 'Attachment;filename="'.$filename.'.xls"');
    	return $response; 
    	 
    }
    
    /**
     * @Route("/{id}/reinvitar_instancia", name="instancia_reinvitar")
     */
    
    public function reinvitarInstancia($id) {
    	
    	$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontró la Instancia de Evento');
        }
        
        $form = $this->createReinvitarForm($id);
        $form->bindRequest($this->getRequest(),$form);
        
        if ($form->isValid()) {
        	$ccColaboradores = $form['ccColaboradores']->getData();
        	$ccEscuela= $form['ccEscuela']->getData();
        	$invitaciones = $em->getRepository('CpmJovenesBundle:Invitacion')->getInvitacionesPendientes($entity);
        	$eventosManager = $this->getEventosManager();
        	foreach ($invitaciones as $invitacion) {
        		$eventosManager->reinvitarProyecto($entity, $invitacion->getProyecto(),$ccEscuela,$ccColaboradores);
        	}
        	$this->setSuccessMessage("Se reenviaron ".count($invitaciones)." pendientes invitaciones");
        	
        }        
        
		
    	return $this->redirect($this->generateUrl('instancia_show', array('id' => $id)));
    }
    
    private function createReinvitarForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    	->add('id', 'hidden')
    	->add('ccColaboradores','checkbox',array('label'=>'Enviar copia del mensaje a los colaboradores', 'required'=>false))
    	->add('ccEscuela','checkbox',array('label'=>'Enviar copia del mensaje a la escuela', 'required'=>false))
    	->getForm()
    	;
    }
    
}