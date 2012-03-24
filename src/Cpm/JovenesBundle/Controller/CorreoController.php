<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Correo;
use Cpm\JovenesBundle\Form\CorreoType;

use Cpm\JovenesBundle\Entity\ProyectoSearch;
use Cpm\JovenesBundle\Form\ProyectoSearchType;


/**
 * Correo controller.
 *
 * @Route("/correo")
 */
class CorreoController extends BaseController
{
    /**
     * Lists all Correo entities.
     *
     * @Route("/", name="correo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Correo')->findAllQuery();
        return $this->paginate($entities);
        
    }

    /**
     * Finds and displays a Correo entity.
     *
     * @Route("/show", name="correo_show")
     * @Template()
     */
    public function showAction()
    {
		$id_correo = $this->getRequest()->get('correo');
		$entity= $this->getRepository("CpmJovenesBundle:Correo")->findOneById($id_correo);
    	
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        return array(
            'entity'      => $entity);
    }

    /**
     * Displays a form to create a new Correo entity.
     *
     * @Route("/new", name="correo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Correo();
        $form   = $this->createForm(new CorreoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Creates a new Correo entity.
     *
     * @Route("/create", name="correo_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Correo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Correo();
        $request = $this->getRequest();
        $form    = $this->createForm(new CorreoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('correo_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    
    /**
     * 
     * Permite enviar un correo a muchos destinatarios
     * @Route("/new_multicast", name="correo_new_multicast")
     * @Template("CpmJovenesBundle:Correo:new_multicast.html.twig")
  
     */
    public function newMulticastAction() {
    	$request = $this->getRequest();
    	
    	$searchValues = new ProyectoSearch();
    	$searchForm = $this->createForm(new ProyectoSearchType(),$searchValues);
    	$proyectos = null;
    	
    	$response = array();
    	if (is_array($request->get("cpm_jovenesbundle_proyectosearchtype")))
    	{
    		$searchForm->bindRequest($request);
    		if ($searchForm->isValid()) {
    			$destinatarios = $searchForm->getData()->getProyectos_seleccionados();
    			echo "sending email to projects: <br/>"; 
    			var_dump($destinatarios);
    			die;
    		}
    	}
    }
}
