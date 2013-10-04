<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;
use Cpm\JovenesBundle\Service\JYM;
use Cpm\JovenesBundle\Entity\Plantilla;

use Cpm\JovenesBundle\Filter\FilterForm;
use Cpm\JovenesBundle\Filter\Filter;
use Cpm\JovenesBundle\Filter\ModelFilterForm;
use Cpm\JovenesBundle\Filter\ModelFilter; 

use Symfony\Component\HttpFoundation\Response;

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
	
	protected function createAccessDeniedException($msg){
		return new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($msg);
	}
	protected function createInvalidArgumentException($msg){
		return new \InvalidArgumentException($msg);
	}
    
	//ABM
	protected function doPersist($entity){
		$em = $this->getDoctrine()->getEntityManager();
        $em->persist($entity);
        $em->flush();
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

	protected function getEstadosManager(){
		return $this->get('cpm_jovenes_bundle.estados_manager');
	}
	
	protected function getChapaManager(){
		return $this->get('cpm_jovenes_bundle.chapa_manager');
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
    
    public function getValidExtensions() {
    	return $valid_extensions = array("doc","docx","odt","pdf","rtf","wps","zip");
    }
    
    
    /**
     * guarda un archivo de un proyecto con el nombre correcto y en el dir. correcto
     */
       public function subir_archivo($file,$proyecto) {
    		$ext = $file->getExtension();
    		if (empty($ext))
    		{
	    		$pos = strrpos($file->getClientOriginalName(), '.');
	    		if($pos!==false) 
	    			$ext = substr($file->getClientOriginalName(), $pos+1);
    		}
    		if (empty($ext))
	   		$ext =  $file->guessExtension();

    		$valid = $this->getValidExtensions();
    		if (in_array($ext,$valid))
    		{
    			$id = $proyecto->getId();
	    		$new_filename = "Proyecto ".$id."_".rand().".$ext";
	    		$file->move($this->getUploadDir()."$id","$new_filename");
	    		return $new_filename;
    		} 	
    		else 
    			$this->setErrorMessage("Los tipos de archivos permitidos son: ".implode(", ",$this->getValidExtensions()));
    		
    		return "";
    	
    }
    
    /**
      * elimina todos los archivos de todos los estados asociados a un proyecto
    */
     public function eliminarArchivosDeProyecto($proyecto) {
     	$em = $this->getEntityManager();
     	$estados = $em->getRepository('CpmJovenesBundle:EstadoProyecto')->getEstadosAnteriores($proyecto);
     	$eliminados = 0;
     	$total = 0;
     	foreach ( $estados as $estado) {
       		if ($archivo = $estado->getArchivo()) {
       			$total++;
       			if (@unlink( "{$this->getUploadDir()}{$proyecto->getId()}/{$archivo}" ) === TRUE)
       				$eliminados++;
       		}
		}
		if ($archivo = $proyecto->getEstadoActual()->getArchivo()) {
				$total++;
				if (@unlink( $this->getUploadDir().$proyecto->getId()."/".$archivo ) === TRUE)
       				$eliminados++;
		}
		
		//ahora borro el directorio tambien
		
		@rmdir( $this->getUploadDir().$proyecto->getId());
		
		return "Archivos eliminados: $eliminados de $total";
     }
    /**
     * 
     */
    public function filterAction(ModelFilter $modelfilter, $index_path, $extra_args=array())
    {
     	list($form, $batch_filter, $entitiesQuery) = $this->getFilterForm($modelfilter);
     	
    	if ($batch_filter->hasBatchAction()){	
			if ($batch_filter->isBatchActionTypeTodos()){
				
				$entities = $entitiesQuery->getResult();
				
			}else{
				$entities = $batch_filter->getSelectedEntities();
				if (count($entities) == 0){
					$this->setInfoMessage("No se seleccionó ningun elemento");
					//FIXME aca deberia hacerse un forward seguro para no perder el filter
//				 	return $this->forward("CpmJovenesBundle:Correo:index");

				 	return $this->redirect($this->generateUrl($index_path));
				}
				
				$entitiesIds = array();
				foreach ( $entities as $entity ) 
		       		$entitiesIds[]=$entity->getId();
					$entitiesQuery = $this->getRepository($modelfilter->getTargetEntity())
					 	->createQueryBuilder('e')
					 	->andWhere('e.id in (:entities)')->setParameter('entities',$entitiesIds)
					 	->getQuery();
				
			}
			//TODO ver si le paso $extra_args al forward
			return $this->forward($batch_filter->getBatchAction(),array('entitiesQuery'=>$entitiesQuery));				
    	} 
    	return $this->getFilterResults($form, $batch_filter,$entitiesQuery,$extra_args);        
    }

	protected function getFilterForm(ModelFilter $modelFilter){
		
		$modelfilter_Form = $modelFilter->createForm($this->getJYM());
 		$filterForm = new FilterForm($modelfilter_Form);
 		$filter = new Filter($modelFilter,$this->getJYM()->getCicloActivo());
		
 		$form = $this->get('form.factory')->create($filterForm, $filter);
		
		
		$request = $this->getRequest();
		
		if ($request->query->get($form->getName())){
			$form->bindRequest($request);
			$queryForm = $request->query->get($form->getName());
			
			if (!empty($queryForm['selectedEntities'])){
				$repo = $this->getRepository($modelFilter->getTargetEntity());
							$selectedEntities = $repo
				 	->createQueryBuilder('e')
				 	->andWhere('e.id in (:entities)')->setParameter('entities',array_values($queryForm['selectedEntities']))
				 	->getQuery()->getResult();
				 	
				$filter->setSelectedEntities($selectedEntities);
			}
			unset($queryForm['selectedEntities'] );
			
		}
		
		unset($form['selectedEntities']);
		
		
//		$modelFilter = $filter->getModelFilter();
		
        $qb = $this->getRepository($modelFilter->getTargetEntity())->filterQuery($modelFilter,$this->getJYM()->getCicloActivo(), $filter->getSortField(), $filter->getSortOrder() );
		$query = $qb->getQuery();
		
		return array($form, $filter, $query);
		
	}
	
	protected function getFilterResults($form, Filter $filter, $query ,$args= array ()){
		
		$paginator = $this->get('ideup.simple_paginator');
		$filter->setPageSize($paginator->getItemsPerPage());
		$filter->setPageNumber($paginator->getCurrentPage());
		
		$entities = $paginator->setItemsPerPage(20, 'entities')->paginate($query,'entities')->getResult();
		
		$args['filter'] = $filter;
		$args['form'] = $form->createView();

		$request = $this->getRequest();
		$vars = $request->getQueryString();
		$vars = preg_replace("/page=(\d+)/","",$vars);
		$vars = preg_replace("/paginatorId=entities/","",$vars);

		$routeName = $request->get('_route');
		if (empty($routeName))
			$routeName = "home";
			
		
		return array_merge( array('entities' => $entities ,  'paginator' => $paginator , 'pagination_route'=>$routeName, 'extraVars'=>"&$vars") , $args);        
	}    
	protected function getEntityForUpdate($repositoryName, $id, $em = null){
		$e = $this->getEntity($repositoryName, $id, $em);
		$this->getJYM()->puedeEditar($e, true);
		return $e;
	}
	
	protected function getEntityForDelete($repositoryName, $id, $em = null){
		$e = $this->getEntity($repositoryName, $id, $em);
		$this->getJYM()->puedeEditar($e, true);
		return $e;
	}
	
	protected function getEntity($repositoryName, $id, $em = null){
		if (is_null($em))
			$em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository($repositoryName)->find($id);

        if (!$entity) 
            throw $this->createNotFoundException('No se encontró la entidad con id '.$id. ' en el respository '.$repositoryName );
        
        return $entity;
	}
	
	protected function createJsonResponse($json_array){
		$response = new Response(json_encode($json_array));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    protected function createSimpleResponse($status_code,$content="") {
    	$response = new Response();

		$response->setContent($content);
		$response->setStatusCode($status_code);
		$response->headers->set('Content-Type', 'text/html');
		return $response;
    }
    
    protected function makeExcel($data,$template,$filename="") {
    	
    	$date = date('d-M-Y');
		$filename = "$filename $date";
        $response = $this->render($template,$data);
        $response->headers->set('Content-Type', 'application/msexcel;  charset=utf-8');
        $response->headers->set('Content-Disposition', 'Attachment;filename="'.$filename.'.xls"');
    	return $response; 
    	
    	
    }
    protected function getSystemStats() {
    	$stats = array(); 
    	$repo = $this->getRepository('CpmJovenesBundle:Proyecto');
    	
    	$ciclo = $this->getJYM()->getCicloActivo();
    	$qb = $repo->createQueryBuilder('p');
    	$stats['total_proyectos'] = $qb->select($qb->expr()->count('p'))
    									->where('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)
    									->getQuery()->getSingleScalarResult();
 																			
    	$stats['total_PrimeraVezDocente'] = $qb->select($qb->expr()->count('p'))->innerJoin('p.coordinador','coordinador')
    																			->where('( coordinador.aniosParticipo like \'{}\' or coordinador.aniosParticipo is NULL )')
    																			->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)
    																			->getQuery()->getSingleScalarResult();
 																			
    	$stats['total_PrimeraVezAlumnos'] = $qb->select($qb->expr()->count('p'))->where('p.esPrimeraVezAlumnos = 1')
    																			->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)
    																			->getQuery()->getSingleScalarResult();
    	$stats['total_PrimeraVezEscuela'] = $qb->select($qb->expr()->count('p'))->where('p.esPrimeraVezEscuela = 1')
    																			->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)
    																			->getQuery()->getSingleScalarResult();
    	 
    	$stats['total_Coordinadores'] = $coordinadores = count( $this->getEntityManager()->createQuery('SELECT DISTINCT u.id FROM CpmJovenesBundle:Proyecto p JOIN p.coordinador u JOIN p.ciclo c Where c = :ciclo')->setParameter('ciclo',$ciclo)->getResult());
    									//count($qb->select($qb->expr()->count('p'))->groupBy('p.coordinador')->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)->getQuery()->getResult());    	
    	return $stats;
    }   

}
