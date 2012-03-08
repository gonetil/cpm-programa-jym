<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Security\Core\SecurityContext;

abstract class BaseController extends Controller
{

	protected function getEntityManager(){
		return $this->getDoctrine()->getEntityManager();
	}
	
	protected function getRepository($entity_name){
		 $repo = $this->getDoctrine()->getEntityManager()->getRepository($entity_name);
		 return $repo;
	}
		
	protected function getSession(){
		return $this->get('request')->getSession();
	}
	
	protected function encodePasswordFor(UserInterface $user, $password){
		$factory = $this->get('security.encoder_factory');
		$encoder = $factory->getEncoder($user);
		return $encoder->encodePassword($password, $user->getSalt());
	}
	
	//flashes
	protected function setSuccessMessage($msg){
		$this->get('session')->setFlash('success', $msg);
	}
	
	protected function setErrorMessage($msg){
		$this->get('session')->setFlash('error', $msg);
	}
	
	protected function enviarMail($destinatario, $codigo_plantilla, $args=array()){
		$plantilla = $this->getRepository('CpmJovenesBundle:Plantilla')->findOneByCodigo($codigo_plantilla);
		$mailer = $this->get('mailer_manager');
		return $mailer->send($destinatario, $plantilla, $args);
    }

	//ABM
	protected function doPersist($entity){
		$em = $this->getDoctrine()->getEntityManager();
        $em->persist($entity);
        $em->flush();
		
	}
	
	protected function isUserAuthenticated() { 
		return $this->get('security.context')->getToken() && $this->get('security.context')->getToken()->isAuthenticated();
	}
	
	protected function getLoggedInUser() { 
		$user = $this->get('security.context')->getToken()->getUser();
		if (!$user) return null; 
		return $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($user->getUsername());
	}
	
	protected function getUserManager(){
		return $this->get('fos_user.user_manager');
	}
	
	protected function getMailer(){
		return $this->get('cpm_jovenes_bundle.mailer');
	}
	
}
