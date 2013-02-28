<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Plantilla;
use FOS\UserBundle\Model\UserInterface;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\Correo;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Cpm\JovenesBundle\Exception\Mailer\InvalidTemplateException;
use Cpm\JovenesBundle\Exception\Mailer\MailCannotBeSentException;

/**
 */
class TwigSwiftMailer implements MailerInterface
{
	const _EMISOR_ANONIMO = "EMISOR_ANONIMO";
	
	protected $jym;
	protected $mailer;
    protected $router;
    protected $twig;
    protected $doctrine;
    protected $parameters;	

    public function __construct(JYM $jym, \Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, $doctrine, $parameters)
    {
        $this->jym = $jym;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = clone $twig;
        $this->twig->setLoader(new \Twig_Loader_String());
        //$this->twig = new \Twig_Environment(new \Twig_Loader_String());
	    $this->doctrine = $doctrine;
        $this->parameters = $parameters;

        if ($this->parameters['setlocaldomain'])
        	$mailer->getTransport()->setLocalDomain('127.0.0.1');
        
    }
	
	public function getCorreoFromPlantilla($codigo_plantilla){
		$plantilla=$this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Plantilla')->findOneByCodigo($codigo_plantilla);
		if (!$plantilla)
			throw new \InvalidArgumentException("No existe la plantilla $codigo_plantilla");
		$correo = new Correo();
		$correo->setAsunto($plantilla->getAsunto());
		$correo->setCuerpo($plantilla->getCuerpo());
		
		return $correo;
	}

	public function sendCorreoEvaluacionProyecto($proyecto,$codigo_plantilla) {
		$correo = $this->getCorreoFromPlantilla($codigo_plantilla);
		$correo->setDestinatario($proyecto->getCoordinador());
		$correo->setProyecto($proyecto);
		
		$cc = array();
		
		foreach($proyecto->getColaboradores() as $colab ){
			$email = $colab->getEmail();
			$nombre = $colab->getNombre() . " " . $colab->getApellido();
			$cc["$email"] = "$nombre" ;
		}
		
		$email =  $proyecto->getEscuela()->getEmail();
		$nombre = $proyecto->getEscuela()->__toString();
		$cc["$email"] = "$nombre";
		
		$context['cc'] = $cc;
		return $this->enviarCorreo($correo,$context);
	}
	
    public function sendConfirmacionAltaProyecto($proyecto)
    {
        $correo = $this->getCorreoFromPlantilla(Plantilla::ALTA_PROYECTO);
        $correo->setDestinatario($proyecto->getCoordinador());
        $correo->setProyecto($proyecto);
        
	    return $this->enviarCorreo($correo);
    }

    public function resolveUrlParameter($pathname, $args)
    {
        return  $this->router->generate($pathname, $args, true);
    }
    
    public function sendConfirmationEmailMessage(UserInterface $usuario)
    {
        $correo = $this->getCorreoFromPlantilla(Plantilla::CONFIRMACION_REGISTRO);
		$correo->setDestinatario($usuario);

        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $usuario->getConfirmationToken()), true);
        $context = array(Plantilla::_URL => $url, self::_EMISOR_ANONIMO => true);
        //FIXME debo capturar las exceptions d eenviar correo?
        return $this->enviarCorreo($correo, $context);
    }

    public function sendResettingEmailMessage(UserInterface $usuario)
    {
        $correo = $this->getCorreoFromPlantilla(Plantilla::RESETEAR_CUENTA);
		$correo->setDestinatario($usuario);

        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $usuario->getConfirmationToken()), true);
        $context = array(Plantilla::_URL => $url, self::_EMISOR_ANONIMO => true);
        
        return $this->enviarCorreo($correo, $context);
    }

    public function enviarAceptacionInvitacion($invitacion)
    {
        $correo = $this->getCorreoFromPlantilla(Plantilla::ACEPTACION_INVITACION);
        $correo->setProyecto($invitacion->getProyecto());
        return $this->enviarCorreoACoordinador($correo, array('invitacion' => $invitacion));
    }
    
    
	/**
     * Enviar un correo al coordinador del proyecto
     */
    public function enviarCorreoACoordinador(Correo $correo, $context=array()) 
    {
    	$proyecto = $correo->getProyecto();
    	if (empty($proyecto)) 
    		throw new \InvalidArgumentException("Debe especificar el proyecto");
    	
    	$correo->setDestinatario($proyecto->getCoordinador());
		
		return $this->enviarCorreo($correo,$context);
    }
    
	/**
     * Enviar un correo a la escuela del proyecto
     */
    public function enviarCorreoAEscuela(Correo $correo, $context=array()) 
    {
    	$proyecto = $correo->getProyecto();
    	if (empty($proyecto)) 
    		throw new \InvalidArgumentException("Debe especificar el proyecto");
    	
    	$correo->setEmail($proyecto->getEscuela()->getEmail());
		
		return $this->enviarCorreo($correo,$context);
    }
    
    /**
     * Enviar un correo a los colaboradores del proyecto
     */
    public function enviarCorreoAColaboradores(Correo $correo, $context=array()) 
    {
    	$proyecto = $correo->getProyecto();
    	if (empty($proyecto)) 
    		throw new \InvalidArgumentException("Debe especificar el proyecto");
    	
	    $cant = 0;
    	foreach($proyecto->getColaboradores() as $colab ){
    			$c = $correo->clonar(true);
				$c->setDestinatario($colab); 
				$this->enviarCorreo($c,$context);
				$cant++;			
		}
		return $cant;
    }

	/**
	 * Envia un Correo.
	 * @throws MailCannotBeSentException
	 * @throws InvalidTemplateException
	 */
	public function enviarCorreo(Correo $correo, $context=array(), $dryrun=false){
		//TODO ver si hay que validar los permisos del sender
		$context[Plantilla::_USUARIO] = $correo->getDestinatario();
		$context[Plantilla::_EMISOR] = $correo->getEmisor();
		$context[Plantilla::_PROYECTO] = $correo->getProyecto();
		$context[Plantilla::_FECHA] = new \DateTime();
		$context[Plantilla::_URL_SITIO] = $this->parameters['url_sitio'];
		if ($dryrun)
			$context['dry-run']=true;
			
		$email = $correo->getEmail();
		if (empty($email) && !empty($context[Plantilla::_USUARIO]))
			$email = $correo->getDestinatario()->getEmail();
			
		//se asume que la plantilla tiene texto twig nomas, nada de HTML
		list($message,$sent) = $this->sendMessage($email, $correo->getAsunto(),$correo->getCuerpo(), null, $context );
		if ($sent)
	        return $this->guardarCorreo($message, $context);
	    else
	    	throw new MailCannotBeSentException($correo);
		
	}
	
    private function sendMessage($to, $subject, $twig_text, $twig_html, $context)
    {
    	try{
	    	$text_template = $this->twig->loadTemplate($twig_text);
			$textBody = $text_template->render($context);
	        
			if($twig_html){
				$html_template= $this->twig->loadTemplate($twig_html);
	        	$htmlBody = $html_template->render($context);
			}
		}catch(\Twig_Error_Syntax $e){
			throw new InvalidTemplateException("Template Invalido",0,$e);
		}catch(\Twig_Error $e){
			throw new InvalidTemplateException("Error con el template ",0,$e);
		}
		
		
		 
    	$fromEmail= $this->parameters['from_email'];
    	$fromEmailTitle= $this->parameters['from_email_title'];
    	
		$message = \Swift_Message::newInstance()
	        ->setSubject($subject)
	        ->setFrom($fromEmail,$fromEmailTitle)
	        ->setTo($to)
	    ;
		if (isset($context['cc']))
			$message->setCC( $context['cc'] );
		
        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
		
		$sent = $this->isDryRun($context) || $this->mailer->send($message);
			    
        return array($message,$sent); 
    }

    private function isDryRun($context){
    	return !empty($context['dry-run']);
    }
    
    private function guardarCorreo(\Swift_Message $message, $context){
    	
        $correo = new Correo();
		$correo->setFecha(new \DateTime());
		
		if (!empty($context[Plantilla::_USUARIO]) && ($context[Plantilla::_USUARIO] instanceof Usuario)){
    		$correo->setDestinatario($context[Plantilla::_USUARIO]);
    	}
		if (!empty($context[Plantilla::_EMISOR])){
			if ($context[Plantilla::_EMISOR] instanceof Usuario)
	    		$correo->setEmisor($context[Plantilla::_EMISOR]);
   			else{
   				//no es un emisor persistible
   			}
    	}elseif (empty($context[self::_EMISOR_ANONIMO])){
    		$emisor = $this->jym->getLoggedInUser();
    		$correo->setEmisor($emisor);
    	}

		if (!empty($context[Plantilla::_PROYECTO]) && ($context[Plantilla::_PROYECTO] instanceof Proyecto)){
    		$correo->setProyecto($context[Plantilla::_PROYECTO]);
    	}
    	
    	$correo->setEmail(implode(', ', array_keys($message->getTo())));
		$correo->setAsunto($message->getSubject());
		$correo->setCuerpo($message->getBody());
		$em=$this->doctrine->getEntityManager();
		$em->persist($correo);
		if  (!$this->isDryRun($context) && (empty($context[Plantilla::_USUARIO]) || ($context[Plantilla::_USUARIO]->getId() != 0)))
			$em->flush();
		return $correo;
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
    	}catch(\Twig_Error_Syntax $e){
			throw new InvalidTemplateException("Template Invalido",0,$e);
		}catch(\Twig_Error $e){
			throw new InvalidTemplateException("Error con el template ",0,$e);
		}
		return true;
    }
    
    public function renderTemplate($twig_template,$context) {
    	try {
    		$template= $this->twig->loadTemplate($twig_template);
    		$rendered = $template->render($context);
    	}catch(\Twig_Error_Syntax $e){
			throw new InvalidTemplateException("Template Invalido",0,$e);
		}catch(\Twig_Error $e){
			throw new InvalidTemplateException("Error con el template ",0,$e);
		}
    	return $rendered;
    }

    public function getParameter($param_name) {
    	if (isset($this->parameters[$param_name]))
    		return $this->parameters[$param_name];
    	else 
    		return null;
    }
}