<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Cpm\JovenesBundle\Entity\Correo;
use Cpm\JovenesBundle\Form\CorreoType;

use Cpm\JovenesBundle\EntityDummy\ProyectoSearch;
use Cpm\JovenesBundle\Form\ProyectoSearchType;

use Cpm\JovenesBundle\EntityDummy\CorreoBatch;
use Cpm\JovenesBundle\Form\CorreoBatchType;

use Cpm\JovenesBundle\Entity\Plantilla;

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
     * Permite enviar un correo a un conjunto de destinatarios seleccionados
     * @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
     */
    public function writeToManyAction($proyectos_query) {
    	
    	$correoBatch = new CorreoBatch();
    	
    	$proyectos = new \Doctrine\Common\Collections\ArrayCollection($proyectos_query->getResult());
    	
    	
    	$correoBatch->setProyectos($proyectos);
    	
    	$correoBatchForm = $this->createForm(new CorreoBatchType(),$correoBatch);
    	return array(
    					'form' => $correoBatchForm->createView(),
    					'proyectos' => $proyectos_query->getResult(),
    				);
    	        	  
    }

    /**
    *
    * Envia un correo masivo
    * @Route("/send_mass_email", name="correo_send_mass_email")
    */
    public function sendMassEmailAction() { 
    	$request = $this->getRequest();
    	$correoBatch = new CorreoBatch();
    	
    	$correoBatchForm = $this->createForm(new CorreoBatchType(),$correoBatch);
    	$correoBatchForm->bindRequest($request);
    	    	
    	if ($correoBatchForm->isValid()) {
    		$repository = $this->getEntityManager()->getRepository('CpmJovenesBundle:Proyecto');
    		$proyectos = $correoBatch->getProyectos();
    		
    		$mailer = $this->getMailer();
    		$valid  = $mailer->isValidTemplate($correoBatch->getCuerpo());
    		
    		if ($valid == "success") {
    			$example = $proyectos[0];
    			$context = array(Plantilla::_USUARIO => $example->getCoordinador(),
    							  Plantilla::_PROYECTO => $example,
    							  Plantilla::_URL => '?',
    							  Plantilla::_URL_SITIO => $mailer->getParameter('url_sitio'),
    							  Plantilla::_FECHA  => new \DateTime()
    			);
    			$template= $mailer->renderTemplate($correoBatch->getCuerpo(),$context);
    			$cuerpo = $template;
    		
	    		if ($correoBatch->getPreview()) //aun no deben mandarse los emails, sino que hay que previsualizarlos 
	    		{ 
	    			$correoBatch->setPreview(false); //para la prox
	    			$correoBatchForm = $this->createForm(new CorreoBatchType(),$correoBatch);
	    			$content = $this->renderView("CpmJovenesBundle:Correo:write_to_many.html.twig",
	    										array('form'   => $correoBatchForm->createView(),
	    		    			    				  'proyectos' => $proyectos,
	    											  'show_preview' => true,
	    											  'asunto' => $correoBatch->getAsunto(), 
	    											  'cuerpo' => $cuerpo ));
	    			$this->setWarnMessage("Por favor, verifique el texto del correo antes de enviarlo");
	    			return new Response($content);
	    		}
    		
    		$correo = new Correo();
    		$correo->setAsunto($correoBatch->getAsunto());
    		$correo->setCuerpo($correoBatch->getCuerpo());
    		$emisor = $this->getLoggedInUser();
    		$cant = 0;
    		foreach ($proyectos as $proyecto) {

    			$correo->setProyecto($proyecto);
    			if ($correoBatch->getCcColaboradores()) {
    				$mailer->enviarCorreoAColaboradores($emisor, $correo);
    				$cant += count($proyecto->getColaboradores());
    			}

    			if ($correoBatch->getCcEscuelas()) {
    				$mailer->enviarCorreoAEscuela($emisor,$correo);
    				$cant++;
    			}

    			if ($ccCoordinadores = $correoBatch->getCcCoordinadores()) {
    				$mailer->enviarCorreoACoordinador($emisor,$correo);
    				$cant++;
    			}

    		}
    		
    		$this->setSuccessMessage("Se enviaron $cant correos satisfactoriamente");
    		
    		return $this->redirect($this->generateUrl('proyecto'));
    		
    		} //valid == success
    	}  // form->isValid
		
    	
    	return array(
    	            'form'   => $correoBatchForm->createView(),
    				'proyectos' => $correoBatch->getProyectos());
    }
}
