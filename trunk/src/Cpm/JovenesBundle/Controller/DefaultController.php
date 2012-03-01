<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Form\RegistroUsuarioType;


class DefaultController extends BaseController
{
	
	/**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
    	return array();
    }
    
    /**
     * @Route("/public/login", name="_login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->getRequest()->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->getRequest()->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
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
     * @Route("/public/recuperar_clave", name="recuperar_clave")
     */
    public function recuperarClaveAction()
    {
    		//TODO
    }
    
    /**
     * @Route("/public/registrarse", name="registrarse")
     * @Template("CpmJovenesBundle:Default:registrarseForm.html.twig")
     */
    public function registrarseAction()
    {
    	$entity  = new Usuario();
  		$request = $this->getRequest();
        $form    = $this->createForm(new RegistroUsuarioType(), $entity);
        $form->bindRequest($request);
		
		$preexistente = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($entity->getEmail());
		
        if (!$preexistente && $form->isValid()) {
        	
			$entity->setClave($this->encodePasswordFor($entity, $entity->getPassword()));
			$this->doPersist($entity);
            
            $this->enviarMail($entity->getEmail(), Plantilla::REGISTRO_USUARIO, array('user'=>$entity));
            
            $this->setSuccessMessage('Se le ha enviado un correo de confirmación. Deberá seguir el enlace que alli se incluye para completar el proceso de registración. ');
            return $this->render('CpmJovenesBundle:Default:login.html.twig');
        }else{
	 		
	 		if ($preexistente)
	            $this->setErrorMessage('Ya existe un usuario con el mismo email ..');
	
	        return $this->render('CpmJovenesBundle:Default:registrarseForm.html.twig', array(
	            'entity' => $entity,
	            'form'   => $form->createView(),
	            'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll() 
	        ));
        }
    }
    
    /**
     * @Route("/public/registrarse_form", name="registrarse_form")
     * @Template()
     */
    public function registrarseFormAction()
    {
    	$entity = new Usuario();
        $form   = $this->createForm(new RegistroUsuarioType(), $entity);
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'distritos' => $this->getRepository('CpmJovenesBundle:Distrito')->findAll() 
        );
    }
    
}
