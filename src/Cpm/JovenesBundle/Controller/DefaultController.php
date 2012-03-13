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
    	$user = $this->getLoggedInUser();
    	if ($this->get("security.context")->isGranted("ROLE_ADMIN")) {
    		return $this->forward("CpmJovenesBundle:Proyecto:index"); 
    	}
    	elseif ($this->get("security.context")->isGranted("ROLE_USER")) {
    		return $this->forward("CpmJovenesBundle:Perfil:index");
    	}
    	else 
    	return $this->forward("CpmJovenesBundle:Default:_login");
    	
    }
    
    /**
     * @Route("/public/login", name="_login")
     * 
     */
    public function loginAction()
    {
    	
        if ($this->getRequest()->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->getRequest()->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        if (get_class($error) =="Symfony\Component\Security\Core\Exception\LockedException"){
        	$error = "Su cuenta se encuentra pendiente de activacion. Siga el enlace que se le envio a su cuenta de correo electrónico.";
        }elseif (is_a($error, "\Exception")){
        	$error = $error->getMessage();
        }
        
        if ($error) 
        	$this->setErrorMessage($error);
        
        return $this->render('CpmJovenesBundle:Default:login.html.twig', array(
            'last_username' => $this->getSession()->get(SecurityContext::LAST_USERNAME)
        ));
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
    	$localidades = $em->getRepository('CpmJovenesBundle:Localidad')->findByDistrito($distrito_id);
    	
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
    	$distritos = $em->getRepository('CpmJovenesBundle:Distrito')->findByRegion($region_id);
    	 
    	$json = array();
    	foreach ($distritos as $dist) {
    		$json[] = array("nombre"=>$dist->getNombre(), "id" => $dist->getId());
    	}
    	$response = new Response(json_encode($json));
    	 
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
/*
    / **
     * @Route("/public/recuperar_clave", name="recuperar_clave_form")
     * @Template()
     * /
    public function recuperarClaveAction()
    {
    	return array();
    }
    
    / **
     * @Method ("post")
     * @Route("/public/recuperar_clave", name="recuperar_clave_submit")
     * /
    public function recuperarClaveSubmitAction()
    {
    	 return $this->loginAction();
    }
    
    / **
     * @Method("post")
     * @Route("/public/registrarse", name="registrarse_submit")
     * 
     * /
    public function registrarseSubmitAction()
    {
    	$entity  = new Usuario();
  		$request = $this->getRequest();
        $form    = $this->createForm(new RegistroUsuarioType(), $entity);
        $form->bindRequest($request);
		
		$preexistente = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($entity->getEmail());
		$mail_enviado = $this->enviarMail($entity->getEmail(), Plantilla::REGISTRO_USUARIO, array('user'=>$entity));
            
        if ($mail_enviado && !$preexistente && $form->isValid()) {
        	$entity->setClave($this->encodePasswordFor($entity, $entity->getPassword()));
			$entity->setEstaHabilitado(false);
			$this->doPersist($entity);
            
            
            $this->setSuccessMessage('Se le ha enviado un correo de confirmación a '.$entity->getEmail()
					.'. Deberá seguir el enlace que alli se incluye para completar el proceso de registración. ');
            return $this->loginAction();
        }else{

	 		if ($preexistente)
	            $this->setErrorMessage('Ya existe un usuario con el mismo email ..');
			elseif (!$mail_enviado)
				$this->setErrorMessage('Lo sentimos pero no se pudo enviar el mail. Intentelo nuevamente y si el problema persiste contactese con la Comision...');
			else
				$this->setErrorMessage('Faltan datos :S');
				
	        return $this->render('CpmJovenesBundle:Default:registrarse.html.twig', array(
	            'entity' => $entity,
	            'form'   => $form->createView(),
	            'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll() 
	        ));
        }
    }
    
    
    / **
     * @Route("/public/registrarse", name="registrarse_form")
     * @Template()
     * /
    public function registrarseAction()
    {
    	$entity = new Usuario();
        $form   = $this->createForm(new RegistroUsuarioType(), $entity);
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll() 
        );
    }

*/
    
}
