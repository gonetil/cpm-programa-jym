<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Voluntario;
use Cpm\JovenesBundle\Form\VoluntarioType;

use Cpm\JovenesBundle\Filter\VoluntarioFilter;
use Cpm\JovenesBundle\Filter\VoluntarioFilterForm;

/**
 * Voluntario controller.
 *
 * @Route("/voluntario")
 */
class VoluntarioController extends BaseController
{
    /**
     * Lists all Voluntario entities.
     *
     * @Route("/", name="voluntario")
     * @Template()
     */
    public function indexAction()
    {
    	return $this->filterAction(new VoluntarioFilter(), 'voluntario');
    	
/*    	$em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('CpmJovenesBundle:Voluntario')->findAllQuery();
        return $this->paginate($entities); 
        */
    }

    /**
     * Finds and displays a Voluntario entity.
     *
     * @Route("/{id}/show", name="voluntario_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Voluntario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voluntario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Voluntario entity.
     *
     * @Route("/new", name="voluntario_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Voluntario();
        $form   = $this->createForm(new VoluntarioType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Voluntario entity.
     *
     * @Route("/create", name="voluntario_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Voluntario:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Voluntario();
        $request = $this->getRequest();
        $form    = $this->createForm(new VoluntarioType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voluntario_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Voluntario entity.
     *
     * @Route("/{id}/edit", name="voluntario_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Voluntario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voluntario entity.');
        }

        $editForm = $this->createForm(new VoluntarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Voluntario entity.
     *
     * @Route("/{id}/update", name="voluntario_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Voluntario:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Voluntario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voluntario entity.');
        }

        $editForm   = $this->createForm(new VoluntarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voluntario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Voluntario entity.
     *
     * @Route("/{id}/delete", name="voluntario_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Voluntario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Voluntario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('voluntario'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
   /**
	 * @Route("/search/{search}" , name="voluntario_online_search")
	 * @param $search
	 */
    public function searchAction($search)
    {
    	if (strlen($search) < 2) return ;
    	$keywords = array();
    	if ($coma = strpos($search,",",2) !== FALSE) {
    		$keywords = explode(",",$search);
    	} else {
    		$keywords = explode(" ",$search);
    	}
    	
    	array_walk($keywords, create_function('&$keyword', '$keyword = trim($keyword);'));
    	
    	$em = $this->getEntityManager();
    	$qb = $em->getRepository('CpmJovenesBundle:Voluntario')->createQueryBuilder('v');
		
		if ($coma != FALSE) //apellido, nombre
		{
			$qb->andWhere($qb->expr()->like('v.apellido', ':apellido'));
			$qb->andWhere($qb->expr()->like('v.nombre', ':nombre'));
			$qb->setParameter('apellido', $keywords[0].'%');
			$qb->setParameter('nombre', $keywords[1].'%');
		}
		else { 
			foreach ( $keywords as $index => $value ) {
	    		$qb->andWhere($qb->expr()->like('v.apellido', ":search_$index"))->setParameter("search_$index","$value%");   
			}
			
		}
		
    	$data = $qb->getQuery()->getResult();

    	$voluntarios = array();
    	foreach ( $data as $voluntario ) {
            $voluntarios[] = array(
								'label'=>$voluntario->getApellido(). ", ". $voluntario->getNombre(), 
								'desc' => $voluntario->getApellido(). ", ". $voluntario->getNombre(),
								'id' => $voluntario->getId(),
								'value' => $voluntario->getId()
							   );
        }
    	return $this->createJsonResponse($voluntarios);
    }
}
