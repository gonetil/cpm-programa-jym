<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Form\RegistroUsuarioType;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends BaseController
{
	
	/**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
    	$usuario = $this->getJYM()->getLoggedInUser(false);
    	if (!$usuario) 
    		return $this->forward("CpmJovenesBundle:Default:_login");	
    	elseif ($usuario->isAdmin()) 
    		return $this->redirect($this->generateUrl('proyecto')); 
    	else
    		return $this->forward("CpmJovenesBundle:Perfil:index");
    }

    /**
     * @Route("/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
    
    /**
    * Busca todas las escuelas de un distrito
    *
    * @Route("/public/find_by_distrito", name="localidad_find_by_distrito")
    */   
    public function findByDistritoAction() {
    	$distrito_id = $this->get('request')->query->get('distrito_id');
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	if ($distrito_id != -1)
    		$localidades = $em->getRepository('CpmJovenesBundle:Localidad')->findByDistrito($distrito_id);
    	else
    		$localidades = $em->getRepository('CpmJovenesBundle:Localidad')->findAllOrdered();
    	
    	$json = array();
    	foreach ($localidades as $loc) {
    		$json[] = array("nombre"=>$loc->getNombre(), "id" => $loc->getId());
    	} 
    	$response = new Response(json_encode($json));
    	
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }    
    

    /**
    * Busca todos los distritos de una region
    *
    * @Route("/public/find_by_region", name="distrito_find_by_region")
    */
    public function findByRegionAction() {
    	$region_id = $this->get('request')->query->get('region_id');
    
    	$em = $this->getDoctrine()->getEntityManager();
    	if ($region_id != -1)
    		$distritos = $em->getRepository('CpmJovenesBundle:Distrito')->findByRegion($region_id);
    	else
    		$distritos = $em->getRepository('CpmJovenesBundle:Distrito')->findAllOrdered();
    	
    	$json = array();
    	foreach ($distritos as $dist) {
    		$json[] = array("nombre"=>$dist->getNombre(), "id" => $dist->getId());
    	}
    	$response = new Response(json_encode($json));
    	 
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
   /**
    * Busca todas las instancias de un evento
    *
    * @Route("/public/find_instancias_by_evento", name="find_instancias_by_evento")
    */
    public function findByEventoAction() {
    	$evento_id = $this->get('request')->query->get('evento_id');
    
    	$em = $this->getDoctrine()->getEntityManager();
    	if ($evento_id != -1)
    		$instancias = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->findByEvento($evento_id);
    	else
    		$instancias = $em->getRepository('CpmJovenesBundle:InstanciaEvento')->findAll();
    	
    	$json = array();
    	foreach ($instancias as $inst) {
    		$json[] = array("nombre"=>$inst->getTitulo(), "id" => $inst->getId());
    	}
    	$response = new Response(json_encode($json));
    	 
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    
    /**
    * @Route("/instructivo_usuarios", name="instructivo_usuarios")
     * @Template("CpmJovenesBundle:Default:instructivo_usuarios.html.twig")
    */
    public function instructivoUsuariosAction() {
    	return array();
    }

    /**
    * @Route("/choose", name="index_si_no")
    * @Template("CpmJovenesBundle:Default:index_si_no.html.twig")
    */
    public function indexSiNO() {
    	return array();
    }
    
    
    
    /**
    * Busca todos los distritos de una region
    *
    * @Route("/correo/find_by_email", name="correo_find_by_id")
    */
    public function findCorreoByIdAction() {
    	$correo_id = $this->get('request')->query->get('correo_id');
    
    	$em = $this->getDoctrine()->getEntityManager();
    	if ($correo_id) {
    		$correo = $em->getRepository('CpmJovenesBundle:Correo')->findOneById($correo_id);
			if (null === $correo) return "";
    	}
    	$json = array(	"asunto" => $correo->getAsunto(), 
    					"cuerpo" => $correo->getCuerpo(), 
    					"fecha" => $correo->getFecha(),
    					"email" => $correo->getEmail(),
    					"path" => $this->generateUrl('correo_show', array('id' => $correo_id)));
    	
    	if ($correo->getDestinatario()) $json["destinatario"] = $correo->getDestinatario();
    	if ($correo->getEmisor()) $json["emisor"] = $correo->getEmisor();
    	if ($correo->getProyecto()) $json["proyecto"] = $correo->getProyecto();
    	
    	$response = new Response(json_encode($json));
    
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
}
