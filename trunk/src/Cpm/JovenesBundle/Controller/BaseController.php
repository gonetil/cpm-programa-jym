<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;
use Cpm\JovenesBundle\Service\JYM;
use Cpm\JovenesBundle\Entity\Plantilla;

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
	protected function setWarnMessage($msg){
		$this->get('session')->setFlash('warning', $msg);
	}
	protected function setErrorMessage($msg){
		$this->get('session')->setFlash('error', $msg);
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
		//TODO ver si es necesario que se levante el user, me pa que es al pedo
		return $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($user->getUsername());
	}
	
	protected function getUserManager(){
		return $this->get('fos_user.user_manager');
	}
	
	protected function getMailer(){
		return $this->get('cpm_jovenes_bundle.mailer');
	}
	
	protected function getEventosManager(){
		return $this->get('cpm_jovenes_bundle.eventos_manager');
	}
	
	protected function paginate($query, $extra_params = array() ){ 
		
		
		$paginator = $this->get('ideup.simple_paginator');
		$entities = $paginator->setItemsPerPage(20, 'entities')->paginate($query,'entities')->getResult();
		
		$request = ($this->container->get('request'));
		
		$routeName = $request->get('_route');

		$vars = $request->getQueryString();
		$vars = preg_replace("/page=(\d+)/","",$vars);
		$vars = preg_replace("/paginatorId=entities/","",$vars);


		if (empty($routeName))
			$routeName = "home";

		return array_merge( array('entities' => $entities ,  'paginator' => $paginator , 'pagination_route'=>$routeName, 'extraVars'=>"&$vars") , $extra_params);
	}
	
	/**
	*
	* Toma una lista de colaboradores, y se fija si ya existen en la BBDD.
	* Si eso sucede, reemplaza el colaborador de la lista por el de la bbdd.
	* Si esto NO sucede, normaliza los datos del colaborador 
	* Esta funcion es usada en PerfilController y en ProyectoController, al crear y editar proyectos
	* @param array $colaboradores
	*/
	protected function procesar_colaboradores($colaboradores) {

		foreach ($colaboradores as $colaborador) {
			$email = $colaborador->getEmail();
			$id = $colaborador->getId();
		
			if ($email == null) //hay un colaborador que no tiene email... o sea, se esta eliminando uno
			{ 
				$colaboradores->removeElement($colaborador);
			}
			elseif ( $c = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($email)) //el colaborador ya existia en la bbdd
			{ //si el email del colaborador estaba en la BBDD, no creo uno nuevo
				$colaboradores->removeElement($colaborador);
				$colaboradores->add($c);
				$c->setApellido(ucwords(strtolower($c->getApellido())));
				$c->setNombre(ucwords(strtolower($c->getNombre())));
	
			} else
			{ //si el colaborador debe ser cargado en la BBDD, le pongo una password vacia
				$colaborador->setApellido(ucwords(strtolower($colaborador->getApellido())));
				$colaborador->setNombre(ucwords(strtolower($colaborador->getNombre())));
				$colaborador->setPassword("");
				$colaborador->setId("");
			}
		}
		return $colaboradores;
	}
	
	/**
	 * 
	 * Cuando un colaborador es eliminado de un proyecto, debe verificarse si tiene sentido mantenerlo en el sistema
	 * Se elimina un colaborador del sistema cuando:
	 * 	1. No es admin ni docente y
	 *  2. No participa en ningún otro proyecto
	 * @param Proyecto $proyecto
	 * @param array(email:String) $antiguos_colaboradores
	 */
	protected function eliminar_usuarios_sueltos($proyecto,$antiguos_colaboradores) {
		$em = $this->getDoctrine()->getEntityManager();
		$colaboradores = $proyecto->getColaboradores();
		
		foreach ($colaboradores as $c)
			if (($pos = array_search($c->getEmail(),$antiguos_colaboradores)) !== FALSE) 
				unset($antiguos_colaboradores[$pos]);

		
		foreach ($antiguos_colaboradores as $email) {
			$colaborador = $this->getRepository('CpmJovenesBundle:Usuario')->findOneByEmail($email);
			if ($colaborador) echo "Deberia borrarse el colaborador ".$colaborador;
			if ( $colaborador //existe el colaborador
				&& (count($colaborador->getProyectosColaborados()) <= 1) //no esta en ningun proyecto, o esta en este solo
				&& (!$colaborador->isEnabled()) //es colaborador solamente
				)
			{
				$em->remove($colaborador);
				$em->flush();
			}
		}
		
	}
	
    protected function getJYM(){
    	//return JYM::instance();
    	return $this->get('cpm_jovenes_bundle.application');
    }
    
    protected function getUploadDir() 
    {
    	$dir = $this->container->getParameter('upload_dir');
    	if (substr($dir,strlen($dir)-1,1) != "/")
    		$dir .= "/";
    	return $dir;
    }
    
}
