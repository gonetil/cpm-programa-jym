<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Etapa;
use Cpm\JovenesBundle\Form\EtapaType;

/**
 * Etapa controller.
 *
 * @Route("/etapa")
 */
class EtapaController extends BaseController
{
	
	
    /**
     * 
     * @Route("/{id}/mover", name="etapa_mover")
     * @Method("get")
     * @Template("CpmJovenesBundle:Etapa:edit.html.twig")
     */
    public function moverAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
		
		$accion = $this->getRequest()->get('accion');
        $etapa1 = $this->getEntityForUpdate('CpmJovenesBundle:Etapa', $id, $em);
		$repo =$em->getRepository('CpmJovenesBundle:Etapa');
		
		try{
			if ($accion == 'subir')
				$etapa2 = $repo->findEtapaAnteriorA($etapa1);
			else		
				$etapa2 = $repo->findEtapaSiguienteA($etapa1);
				
			$old_num=$etapa2->getNumero();
			$etapa2->setNumero($etapa1->getNumero());
			$etapa1->setNumero($old_num);
			
			$em->persist($etapa1);
            $em->persist($etapa2);
            $em->flush();
            
            $this->setSuccessMessage("Se movio la etapa satisfactoriamente");
			
    	}catch(\OutOfRangeException $e){
			$this->setErrorMessage("No se puede $accion la etapa, no hay nada mas ".($accion=='subir'?'arriba':'abajo'));
		}
		return $this->redirect($this->generateUrl('etapa'));
    }
    
    /**
     * Lists all Etapa entities.
     *
     * @Route("/", name="etapa")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Etapa')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Etapa entity.
     *
     * @Route("/{id}/show", name="etapa_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Etapa', $id);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Etapa entity.
     *
     * @Route("/new", name="etapa_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Etapa();
        $form   = $this->createForm(new EtapaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Etapa entity.
     *
     * @Route("/create", name="etapa_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Etapa:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Etapa();
        $request = $this->getRequest();
        $form    = $this->createForm(new EtapaType(), $entity);
        $form->bindRequest($request);
		$em = $this->getDoctrine()->getEntityManager();
           
		//Calculo el numero de etapa
        $max=0;
        $etapas = $em->getRepository('CpmJovenesBundle:Etapa')->findAll();
        foreach($etapas as $etapa){
        	$max=max($etapa->getNumero(), $max);
		}
        $entity->setNumero($max + 1);
            
        if ($form->isValid()) {
        	
        	try {
			    //chequeo que el action exista
			    $url = $this->get('router')->generate($entity->getProyectosVigentesAction());
			   
			    $em->persist($entity);
	            $em->flush();
				$this->setSuccessMessage("Se creo la etapa, ahora complete todas sus acciones");
	            
	            return $this->redirect($this->generateUrl('etapa_edit', array('id' => $entity->getId())));
		
			} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
			    $this->setErrorMessage("El action ".$entity->getProyectosVigentesAction()." no es válido");
			}
		}

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Etapa entity.
     *
     * @Route("/{id}/edit", name="etapa_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Etapa', $id);

        $editForm = $this->createForm(new EtapaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Etapa entity.
     *
     * @Route("/{id}/update", name="etapa_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Etapa:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Etapa', $id, $em);

        $editForm   = $this->createForm(new EtapaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
        	
        	try {
			    //chequeo que el action exista
			    $url = $this->get('router')->generate($entity->getProyectosVigentesAction());
			    
	        	
	            $em->persist($entity);
	            $em->flush();
				$this->setSuccessMessage("Se guardo la etapa");
	            return $this->redirect($this->generateUrl('etapa_show', array('id' => $entity->getId())));
	            
			} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
			    $this->setErrorMessage("El action ".$entity->getProyectosVigentesAction()." no es válido");
			}
			        	
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Etapa entity.
     *
     * @Route("/{id}/delete", name="etapa_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
    	//TODO habilitar la eliminación
    	$this->setErrorMessage('La eliminación de etapas está deshabilitada');
        return $this->redirect($this->generateUrl('etapa'));
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Etapa', $id, $em);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('etapa'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
