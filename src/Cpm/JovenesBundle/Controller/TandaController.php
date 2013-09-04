<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Tanda;
use Cpm\JovenesBundle\Form\TandaType;

use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Entity\AuditorioDia;

/**
 * Tanda controller.
 *
 * @Route("/tanda")
 */
class TandaController extends BaseController
{
    /**
     * Lists all Tanda entities.
     *
     * @Route("/", name="tanda")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
		
		$ciclo = $this->getJYM()->getCicloActivo();
		$qb = $em->getRepository('CpmJovenesBundle:Evento')->createQueryBuilder('e')->select('e')->where('e.ciclo = :ciclo')->setParameter('ciclo',$ciclo);

		$eventos = $qb->getQuery()->getResult();

        $entities = $em->getRepository('CpmJovenesBundle:Tanda')->findAll();

        return array('entities' => $entities, 'eventos'=>$eventos);
    }

    /**
     * Finds and displays a Tanda entity.
     *
     * @Route("/{id}/show", name="tanda_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tanda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tanda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tanda entity.
     *
     * @Route("/new", name="tanda_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tanda();
        $form   = $this->createForm(new TandaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tanda entity.
     *
     * @Route("/create", name="tanda_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tanda:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Tanda();
        $request = $this->getRequest();
        $form    = $this->createForm(new TandaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tanda_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tanda entity.
     *
     * @Route("/{id}/edit", name="tanda_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tanda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tanda entity.');
        }

        $editForm = $this->createForm(new TandaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tanda entity.
     *
     * @Route("/{id}/update", name="tanda_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tanda:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Tanda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tanda entity.');
        }

        $editForm   = $this->createForm(new TandaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tanda_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tanda entity.
     *
     * @Route("/{id}/delete", name="tanda_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Tanda')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tanda entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tanda'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    
    
    /**
     * Crea las tandas a partir de las instancias del evento Chapa.
     *
     * @Route("init_tandas", name="tanda_init")
     * @Method("post")
     */
    public function initializeTanda() {
    	$evento_id = $this->getRequest()->get('evento_chapa');
    	
    	$evento = $this->getEntity('CpmJovenesBundle:Evento', $evento_id);
    	if (!$evento) {
    		throw $this->createNotFoundException('Evento no encontrado');
    	}
    	
    	$chapaManager = $this->getChapaManager();
    	try {
    		$chapaManager->inicializarTandas($evento);
    		$this->setSuccessMessage("Se crearon todas tandas, con sus dias y auditorios");
    	} catch (\Exception $e) {
    		$this->setErrorMessage('Error al inicializar las tandas de Chapadmalal. Mensaje: '.$e);
            throw $e;
    	}	
    	
		return $this->redirect($this->generateUrl('tanda'));
    }
    
    
}
