<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Form\PlantillaType;

/**
 * Plantilla controller.
 *
 * @Route("/plantilla")
 */
class PlantillaController extends BaseController
{
    /**
     * Lists all Plantilla entities.
     *
     * @Route("/", name="plantilla")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Plantilla')->findAllQuery();

        return $this->paginate($entities);
    }

    /**
     * Finds and displays a Plantilla entity.
     *
     * @Route("/{id}/show", name="plantilla_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Plantilla entity.
     *
     * @Route("/new", name="plantilla_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Plantilla();
        $form   = $this->createForm(new PlantillaType(), $entity);
		$form->remove('codigo');
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Plantilla entity.
     *
     * @Route("/create", name="plantilla_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Plantilla:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Plantilla();
        $request = $this->getRequest();
        $form    = $this->createForm(new PlantillaType(), $entity);
        $form->bindRequest($request);

        $template_is_correct = $this->isValidTemplate($entity->getCuerpo());
        $entity->setCodigo($this->slug($entity->getAsunto()));
        if ($form->isValid() && ($template_is_correct == "success")) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plantilla_show', array('id' => $entity->getId())));
            
        }
		
        
		$retorno = array(
			            'entity' => $entity,
			            'form'   => $form->createView()
			        );
		if ($template_is_correct != "success") $retorno['template_error'] = "Error en la plantilla ($template_is_correct)";
        return $retorno; 
    }
    
    protected function slug($str)
    {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
    return $str;
    }

    /**
     * Displays a form to edit an existing Plantilla entity.
     *
     * @Route("/{id}/edit", name="plantilla_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $editForm = $this->createForm(new PlantillaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Plantilla entity.
     *
     * @Route("/{id}/update", name="plantilla_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Plantilla:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $editForm   = $this->createForm(new PlantillaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        
        $parsed = $this->isValidTemplate($entity->getCuerpo());

        if ($editForm->isValid() && ($parsed == "success")) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plantilla_edit', array('id' => $id)));
        }

        $retorno = array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        
        if ($parsed != "success") $retorno['template_error'] = "Error en la plantilla ($parsed)";
        	
        return $retorno;
         
    }

    /**
     * Deletes a Plantilla entity.
     *
     * @Route("/{id}/delete", name="plantilla_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Plantilla')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plantilla entity.');
            }
            if (!$entity->getPuedeBorrarse())
				$this->setErrorMessage("No se pueden borrar las plantillas del sistema");
			else{
				$this->setSuccessMessage("La plantilla ha sido borrada");
				$em->remove($entity);
	            $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('plantilla'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * @param string $template : un string con tags twig dentro
     * returns boolean
     */
    private function isValidTemplate($template) 
    {
    	$twig = $this->get('twig');
    	try {
    		$token_stream = $twig->tokenize($template);
    		$twig->parse($token_stream);
    	} catch (\Twig_Error_Syntax $e) {
    		
    		return $e->getMessage(); 
    	}
    	return "success";
    	
    }
}
