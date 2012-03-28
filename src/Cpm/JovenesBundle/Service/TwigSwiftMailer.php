<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Plantilla;
use FOS\UserBundle\Model\UserInterface;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Correo;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 */
class TwigSwiftMailer implements MailerInterface
{
	protected $mailer;
    protected $router;
    protected $twig;
    protected $doctrine;
    protected $parameters;	

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, $doctrine, $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = clone $twig;
        $this->twig->setLoader(new \Twig_Loader_String());
        //$this->twig = new \Twig_Environment(new \Twig_Loader_String());
	    $this->doctrine = $doctrine;
        $this->parameters = $parameters;
        
    }
    
	protected function getPlantilla($codigo_plantilla){
		$plantilla=$this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Plantilla')->findOneByCodigo($codigo_plantilla);
		if (!$plantilla)
			throw new \InvalidArgumentException("No existe la plantilla $codigo_plantilla");
		return $plantilla;
	}
	
    public function sendConfirmationEmailMessage(UserInterface $usuario)
    {
        $plantilla = $this->getPlantilla(Plantilla::CONFIRMACION_REGISTRO);
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $usuario->getConfirmationToken()), true);
        $context = array(Plantilla::_URL => $url);

        return $this->sendPlantilla($plantilla, $usuario, $context);
    }

    public function sendResettingEmailMessage(UserInterface $usuario)
    {
        $plantilla = $this->getPlantilla(Plantilla::RESETEAR_CUENTA);
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $usuario->getConfirmationToken()), true);
        $context = array(Plantilla::_URL => $url);
        return $this->sendPlantilla($plantilla, $usuario, $context);
    }

    public function getParameter($param_name) {
    	if (isset($this->parameters[$param_name]))
    		return $this->parameters[$param_name];
    	else 
    		return null;
    }
	public function sendPlantilla($plantilla, Usuario $usuario, $context, $sender = null){
		$context[Plantilla::_USUARIO] = $usuario;
		$context[Plantilla::_FECHA] = new \DateTime();
		$context[Plantilla::_URL_SITIO] = $this->parameters['url_sitio'];
		if (!empty($sender)) 
			$context[Plantilla::_EMISOR] = $sender;
		
		//se asume que la plantilla tiene texto twig nomas, nada de HTML
		return $this->sendMessage($usuario->getEmail(), $plantilla->getAsunto(),$plantilla->getCuerpo(), null, $context );
	}
	
    public function sendMessage($to, $subject, $twig_text, $twig_html, $context)
    {
    	$text_template = $this->twig->loadTemplate($twig_text);
		$textBody = $text_template->render($context);
        
		if($twig_html){
			$html_template= $this->twig->loadTemplate($twig_html);
        	$htmlBody = $html_template->render($context);
		}
		
    	$fromEmail= $this->parameters['from_email'];

		$message = \Swift_Message::newInstance()
	        ->setSubject($subject)
	        ->setFrom($fromEmail)
	        ->setTo($to)
	    ;
		
        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
		
		$sent= $this->mailer->send($message);
		if ($sent)
	        $this->guardarCorreo($message, $context);
		
        return $sent; 
    }
    
    public function guardarCorreo($message, $context){
    	
        $correo = new Correo();
		$correo->setFecha(new \DateTime());
		
		if (!empty($context[Plantilla::_USUARIO]) && ($context[Plantilla::_USUARIO] instanceof Usuario)){
    		$correo->setDestinatario($context[Plantilla::_USUARIO]);
    	}
		if (!empty($context[Plantilla::_EMISOR]) && ($context[Plantilla::_EMISOR] instanceof Usuario)){
    		$correo->setEmisor($context[Plantilla::_EMISOR]);
    	}

		if (!empty($context[Plantilla::_PROYECTO]) && ($context[Plantilla::_PROYECTO] instanceof Proyecto)){
    		$correo->setProyecto($context[Plantilla::_PROYECTO]);
    	}
    	
    	$correo->setEmail(implode(', ', array_keys($message->getTo())));
		$correo->setAsunto($message->getSubject());
		$correo->setCuerpo($message->getBody());
		$em=$this->doctrine->getEntityManager();
		$em->persist($correo);
		if  ( (!empty($context[Plantilla::_USUARIO])) && ($context[Plantilla::_USUARIO]->getId()))
			$em->flush();
		
    } 
    
    /**
    * @param string $template : un string con tags twig dentro
    * returns boolean
    */
    public function isValidTemplate($twig_template)
    {
    	$twig = $this->twig;
    	try {
    		$token_stream = $twig->tokenize($twig_template);
    		$twig->parse($token_stream);
    	} catch (\Twig_Error_Syntax $e) {
    
    		return $e->getMessage();
    	}
    	return "success";
    }
    
    
    public function renderTemplate($twig_template,$context) {
    	$template= $this->twig->loadTemplate($twig_template);
    	$rendered = $template->render($context);
    	return $rendered;
    }

}