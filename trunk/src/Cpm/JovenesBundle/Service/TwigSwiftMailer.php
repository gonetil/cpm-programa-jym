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
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = Plantilla::RESETEAR_CUENTA;
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );
        $this->sendMessage($template, $context, $user->getEmail());
    }

    protected function sendMessage($codigo_plantilla, $context, $toEmail)
    {
    	$fromEmail= $this->parameters['from_email'];
        
        $plantilla=$this->doctrine->getEntityManager()->getRepository('CpmJovenesBundle:Plantilla')->findOneByCodigo($codigo_plantilla);
		//fixme validar $plantilla
		
		$template = $this->twig->loadTemplate($plantilla->getCuerpo());
        
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

		$message = \Swift_Message::newInstance()
	        ->setSubject($plantilla->getAsunto())
	        ->setFrom($fromEmail)
	        ->setTo($toEmail)
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