<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;
use Cpm\JovenesBundle\Service\JYM;

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
	protected function setInfoMessage($msg){
		$this->get('session')->setFlash('info', $msg);
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
	
	protected function paginate($query, $extra_params = array() ){ 
		
		
		$paginator = $this->get('ideup.simple_paginator');
		$entities = $paginator->setItemsPerPage(20, 'entities')->paginate($query,'entities')->getResult();
		
		$routeName = $this->container->get('request')->get('_route');
		if (empty($routeName))
			$routeName = "home";

		return array_merge( array('entities' => $entities ,  'paginator' => $paginator , 'pagination_route'=>$routeName) , $extra_params);
	}
	
	
    protected function getJYM(){
    	//return JYM::instance();
    	return $this->get('cpm_jovenes_bundle.application');
    }
}
