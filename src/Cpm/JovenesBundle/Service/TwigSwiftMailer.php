<?php

namespace Cpm\JovenesBundle\Service;

use Cpm\JovenesBundle\Entity\Plantilla;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Christophe Coevoet <stof@notk.org>
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

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = Plantilla::CONFIRMACION_REGISTRO;
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'confirmationUrl' => $url
        );

        return $this->sendPlantilla($template, $user, $context);
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = Plantilla::RESETEAR_CUENTA;
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'confirmationUrl' => $url
        );
        return $this->sendPlantilla($template, $user, $context);
    }

	protected function sendPlantilla($codigo_plantilla,UserInterface $user, $context=array()){
		$context['user'] = $user;
		$plantilla=$this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Plantilla')->findOneByCodigo($codigo_plantilla);
		
		if (!$plantilla)
			throw new \InvalidArgumentException("No existe la plantilla $codigo_plantilla");
		
		//se asume que la plantilla tiene texto twig nomas, nada de HTML
		return $this->sendMessage($user->getEmail(), $plantilla->getAsunto(),$plantilla->getCuerpo(), null, $context );
	}
	
    protected function sendMessage($to, $subject, $twig_text, $twig_html, $context)
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

        return $this->mailer->send($message);
    }
}