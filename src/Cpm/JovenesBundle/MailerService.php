<?php

namespace Cpm\JovenesBundle;

class MailerService
{
    protected $mailer;

    protected $from;

    public function __construct(\Swift_Mailer $mailer, $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

	public function send($destinatarios, $plantilla, $arguments){
        
        //if (empty($plantilla)) throw new Runtime
       //FIMXE handle error && escape
       
        $message = \Swift_Message::newInstance()
	        ->setSubject($plantilla->getAsunto())
	        ->setFrom($this->from)
	        ->setTo($destinatarios)
	        ->setBody($plantilla->getCuerpo())
	    ;
	    
    	return $this->mailer->send($message);
		
	}
}