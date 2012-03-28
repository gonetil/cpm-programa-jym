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

use Cpm\JovenesBundle\EntityDummy\CorreoMasivo;
use Cpm\JovenesBundle\Form\CorreoMasivoType;

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
     * @Route("/write_to_selected", name="correo_write_to_selected")
     * @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
     */
    public function writeToSelectedAction() {
    	$request = $this->getRequest();
    	
    	$searchValues = new ProyectoSearch();
    	$searchForm = $this->createForm(new ProyectoSearchType(),$searchValues);
    	
    	$proyectos = null;
    	
    	$response = array();
    	if (is_array($request->get("cpm_jovenesbundle_proyectosearchtype")))
    	{
    		$searchForm->bindRequest($request);
    		$repository = $this->getEntityManager()->getRepository('CpmJovenesBundle:Proyecto');
    		
    		if ($searchForm->isValid()) {
    			$destinatarios = $searchForm->getData()->getProyectos_seleccionados();
    			if (count($destinatarios) > 0)
    				$proyectos = $proyectos = $repository->findAllInArray($destinatarios);
    		}
    	}
    	
    	$correoMasivo = new CorreoMasivo();
        $correoMasivoForm = $this->createForm(new CorreoMasivoType(),$correoMasivo);

        return array('form' => $correoMasivoForm->createView(),
        			 'proyectos' => (($proyectos==null)?array():$proyectos->getResult())
        );
        
    }
    
    /**
     * 
     * Permite enviar un correo a un conjunto de destinatarios seleccionados
     * @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
     */
    public function writeToManyAction($proyectos_query) {
    	$correoMasivo = new CorreoMasivo();
    	$correoMasivoForm = $this->createForm(new CorreoMasivoType(),$correoMasivo);
    	
    	$proyectos = $proyectos_query->getResult();
    	
    	return array('form' => $correoMasivoForm->createView() , 
    			   	 'proyectos' => $proyectos
    				);
    	        	  
    }

    /**
    *
    * Permite enviar un correo a todos los proyectos resultantes del filtro (o a todos en caso de no encontrarse el filtro)
    * @Route("/write_to_all", name="correo_write_to_all_results")
    * @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
    */
    public function writeToAllAction() { 
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('CpmJovenesBundle:Proyecto');
        $ciclo = $this->getJYM()->getCicloActivo();
        $request = $this->getRequest();

        $searchValues = new ProyectoSearch();
        $searchForm = $this->createForm(new ProyectoSearchType(),$searchValues);
        $proyectos = null;
    	
        if (is_array($request->get("cpm_jovenesbundle_proyectosearchtype")))
        {
        	$searchForm->bindRequest($request);
        	if ($searchForm->isValid())
        		$proyectos =$repository ->findBySearchCriteriaQuery($searchForm->getData(),$ciclo);
        } else {
        	$proyectos = $repository->findAllQuery($ciclo);
        }

        $correoMasivo = new CorreoMasivo();
        $correoMasivoForm = $this->createForm(new CorreoMasivoType(),$correoMasivo);
        
        return array('form' => $correoMasivoForm->createView(),
        			 'proyectos' => $proyectos->getResult());
    }
    
    /**
    *
    * Envia un correo masivo
    * @Route("/send_mass_email", name="correo_send_mass_email")
    * @Template("CpmJovenesBundle:Correo:send_mass_email.html.twig")
    */
    public function sendMassEmailAction() { 
    	$request = $this->getRequest();
    	$correoMasivo = new CorreoMasivo();
    	
    	$correoMasivoForm = $this->createForm(new CorreoMasivoType(),$correoMasivo);
    	$correoMasivoForm->bindRequest($request);
    	
    	if ($correoMasivoForm->isValid()) {
    		

    		$repository = $this->getEntityManager()->getRepository('CpmJovenesBundle:Proyecto');
    		$destinatarios = $correoMasivo->getProyectos();
    		

    		if (count($destinatarios) > 0)
    			$proyectos = $repository->findAllInArray($destinatarios)->getResult();
    		else $proyectos=array();
    		
    		$mailer = $this->getMailer();
    		$valid  = $mailer->isValidTemplate($correoMasivo->getCuerpo());
    		
    		if ($valid == "success") {
    			$example = $proyectos[0];
    			$context = array(Plantilla::_USUARIO => $example->getCoordinador(),
    							  Plantilla::_PROYECTO => $example,
    							  Plantilla::_URL => '?',
    							  Plantilla::_URL_SITIO => $mailer->getParameter('url_sitio'),
    							  Plantilla::_FECHA  => new \DateTime()
    			);
    			$template= $mailer->renderTemplate($correoMasivo->getCuerpo(),$context);
    			$cuerpo = $template;
    		
    		
    		
    		if ($correoMasivo->getPreview()) { //aun no deben mandarse los emails, sino que hay que previsualizarlos
    			$correoMasivo->setPreview(false); //para la prox
    			$correoMasivoForm = $this->createForm(new CorreoMasivoType(),$correoMasivo);
    			$content = $this->renderView("CpmJovenesBundle:Correo:write_to_many.html.twig",
    										array('form'   => $correoMasivoForm->createView(),
    		    			    				  'proyectos' => $proyectos,
    											  'show_preview' => true,
    											  'asunto' => $correoMasivo->getAsunto(), 
    											  'cuerpo' => $cuerpo ));
    			$this->setWarnMessage("Por favor, verifique el texto del correo antes de enviarlo");
    			return new Response($content);
    		}
    		
    		$ccEscuelas = $correoMasivo->getCcEscuelas();
    		$ccColaboradores = $correoMasivo->getCcColaboradores();
    		$ccCoordinadores = $correoMasivo->getCcCoordinadores();
    		$cuerpo = $correoMasivo->getCuerpo();
    		$asunto = $correoMasivo->getAsunto();
    		
    		foreach ($proyectos as $proyecto) {
    			$this->enviarMailAProyecto($proyecto,$asunto,$cuerpo,$ccCoordinadores,$ccEscuelas,$ccColaboradores,$context);
    		}
    		$this->setSuccessMessage("Los correos fueron enviados satisfactoriamente");
    		
    		return $this->redirect($this->generateUrl('proyecto'));
    		
    		} //valid == success
    	} // form->isValid
		
    	return array(
    	            'form'   => $correoMasivoForm->createView(),
    				'proyectos' => $correoMasivoForm->getData()->getProyectos());
    }
}
