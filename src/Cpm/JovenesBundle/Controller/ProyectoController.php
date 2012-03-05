<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoType;
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Entity\Usuario;

/**
 * Proyecto controller.
 *
 * @Route("/proyecto")
 */
class ProyectoController extends BaseController
{
    /**
     * Lists all Proyecto entities.
     *
     * @Route("/", name="proyecto")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Proyecto')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Proyecto entity.
     *
     * @Route("/{id}/show", name="proyecto_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyecto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Proyecto entity.
     *
     * @Route("/new", name="proyecto_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Proyecto();
        $form   = $this->createForm(new ProyectoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Proyecto entity.
     *
     * @Route("/create", name="proyecto_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Proyecto:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Proyecto();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProyectoType(), $entity);
        $form->bindRequest($request);

        $colaboradores = $entity->getColaboradores();
        foreach ($colaboradores as $colaborador) {
        	if ($c = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($colaborador->getEmail())) //el colaborador ya existia en la bbdd
        	{
        		$colaboradores->removeElement($colaborador);
        		$colaboradores->add($c);
        	}
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('proyecto_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Proyecto entity.
     *
     * @Route("/{id}/edit", name="proyecto_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyecto entity.');
        }

        $editForm = $this->createForm(new ProyectoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Proyecto entity.
     *
     * @Route("/{id}/update", name="proyecto_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Proyecto:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyecto entity.');
        }

        
        $editForm   = $this->createForm(new ProyectoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $colaboradores = $entity->getColaboradores();
        foreach ($colaboradores as $colaborador) {
        	if ($c = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($colaborador->getEmail())) //el colaborador ya existia en la bbdd
        	{
        		$colaboradores->removeElement($colaborador);
        		$colaboradores->add($c);
        	}
        }
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('proyecto_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Proyecto entity.
     *
     * @Route("/{id}/delete", name="proyecto_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Proyecto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('proyecto'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /** 
     * Quita un colaborador de un proyecto. No elimina al usuario
     * 
     *  @Route("/remove_colaborador" , name="proyecto_quitar_colaborador")
     * 
     * */
	public function quitarColaboradorDeProyecto() {
		$id_proyecto = $this->getRequest()->get('id_proyecto');
		$email_colaborador = $this->getRequest()->get('email_colaborador');
		 
		$proyecto = $this->getRepository('CpmJovenesBundle:Proyecto')->findOneById($id_proyecto);

		$em = $this->getEntityManager();
		
		foreach ($proyecto->getColaboradores() as $colab) { 
			if ($colab->getEmail() == $email_colaborador) { 
				$proyecto->getColaboradores()->removeElement($colab);
				$colab->getColaboraEn()->removeElement($proyecto);
				$em->persist($proyecto);
				$em->persist($colab);
				$em->flush();
				return new Response('success');
			}
		}


		return new Response("error");
		
	}
    
}