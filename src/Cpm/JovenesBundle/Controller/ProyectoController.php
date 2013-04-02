<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Cpm\JovenesBundle\Controller\BaseController;
use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Form\ProyectoType;
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Entity\Usuario;
use Cpm\JovenesBundle\Entity\EstadoProyecto;
use Cpm\JovenesBundle\Form\EstadoProyectoType;

use Cpm\JovenesBundle\Filter\ProyectoFilter;
use Cpm\JovenesBundle\Filter\ProyectoFilterForm;

use Cpm\JovenesBundle\Entity\Comentario;

/**
 * Proyecto controller.
 *
 * @Route("/proyecto")
 */
class ProyectoController extends BaseController
{
	
    /**
     * Lists all Proyecto entities.
     * @Method("get")
     * @Route("/", name="proyecto")
     * @Template()
     */
    public function indexAction()
    {
    	$stats = $this->getSystemStats();
    	$estadosManager = $this->getEstadosManager(); //agrego esto para que las constantes aparezcan en el twig
		return $this->filterAction(new ProyectoFilter(), 'proyecto', $stats);
    }
    
    /**
     * Lists all Proyecto entities.
     * @Method("get")
     * @Route("/proyectos_presentados", name="proyectos_presentados")
     * @Template("CpmJovenesBundle:Proyecto:index.html.twig")
     */
	public function proyectosPresentadosAction() {
    	$stats = $this->getSystemStats();
    	$estadosManager = $this->getEstadosManager(); //agrego esto para que las constantes aparezcan en el twig
    	$filter = new ProyectoFilter();
    	$filter->getEstadoFilter()->setConArchivo(1);
    	$this->setInfoMessage("Proyectos vigentes: proyectos en estado PRESENTADO hacia adelante");
		return $this->filterAction($filter, 'proyecto', $stats);
		
	}
	
    /**
     * Lista todos los proyectos que fueron presentados y su estado es APROBADO o APROBADO C
     * @Method("get")
     * @Route("/proyectos_aprobados", name="proyectos_aprobados")
     * @Template("CpmJovenesBundle:Proyecto:index.html.twig")
     */
	public function proyectosAprobadosAction() {
    	$stats = $this->getSystemStats();
    	$estadosManager = $this->getEstadosManager(); //agrego esto para que las constantes aparezcan en el twig
    	$filter = new ProyectoFilter();
    	$filter->getEstadoFilter()->setNota(ESTADO_APROBADO_Y_APROBADO_C);
    	$this->setInfoMessage("Proyectos vigentes: proyectos evaluados y en estado APROBADO o APROBADO C");
		return $this->filterAction($filter, 'proyecto', $stats);
	}
	
	
    private function getSystemStats() {
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
    	 
    	$stats['total_Coordinadores'] = count($qb->select($qb->expr()->count('p'))->groupBy('p.coordinador')
    																			->andWhere('p.ciclo = :ciclo')->setParameter('ciclo',$ciclo)
    																			->getQuery()->getResult());    	
    	return $stats;
    }
    
    /**
     * Finds and displays a Proyecto entity.
     *
     * @Route("/{id}/show", name="proyecto_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getEntity('CpmJovenesBundle:Proyecto', $id, $em);
        
		$postits = $em->getRepository('CpmJovenesBundle:Comentario')->findBy(array('proyecto'=>$entity->getId(), 'tipo'=> Comentario::POSTIT));
		$comentarios = $em->getRepository('CpmJovenesBundle:Comentario')->findBy(array('proyecto'=>$entity,'tipo'=>Comentario::COMENTARIO));
		$tareas = $em->getRepository('CpmJovenesBundle:Comentario')->findBy(array('proyecto'=>$entity,'tipo'=>Comentario::TAREA));
		
        $deleteForm = $this->createDeleteForm($id);
        $nuevoEstado = new EstadoProyecto();
        $estadoForm = $this->createForm(new EstadoProyectoType($this->getEstadosManager()),$nuevoEstado );

        $historialEstados = $em->getRepository('CpmJovenesBundle:EstadoProyecto')->getEstadosAnteriores($entity);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'estado_form' => $estadoForm->createView(),
            'estados_anteriores' => $historialEstados, 
            'postits' => $postits,
            'comentarios' => $comentarios,
            'tareas' => $tareas
            );
    }		

    /**
     * Displays a form to create a new Proyecto entity.
     *
     * @Route("/new", name="proyecto_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Proyecto();
        $form   = $this->createForm(new ProyectoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Proyecto entity.
     *
     * @Route("/create", name="proyecto_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Proyecto:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Proyecto();
        $request = $this->getRequest();
        $jym = $this->getJYM();
        $entity->setCiclo($jym->getCicloActivo());
        
        $form    = $this->createForm(new ProyectoType(), $entity);
        $form->bindRequest($request);

        $this->procesar_colaboradores($entity->getColaboradores());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Proyecto creado satisfactoriamente");
            return $this->redirect($this->generateUrl('proyecto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Proyecto entity.
     *
     * @Route("/{id}/edit", name="proyecto_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id);
        $editForm = $this->createForm(new ProyectoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Proyecto entity.
     *
     * @Route("/{id}/update", name="proyecto_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Proyecto:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id,$em);
        
        $colabs = array(); //colaboradores que tenia la entidad
        foreach ($entity->getColaboradores() as $c) {
        	$colabs[] = $c->getEmail();
        }
        
        $editForm   = $this->createForm(new ProyectoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $this->procesar_colaboradores($entity->getColaboradores());
                        
        if ($editForm->isValid()) {
            $em->persist($entity);
            //los colaboradores eliminados que no estan en otro proyecto seran eliminados
            $this->eliminar_usuarios_sueltos($entity,$colabs);
            
            $em->flush();
            $this->setSuccessMessage("Proyecto fue modificado satisfactoriamente");
            return $this->redirect($this->generateUrl('proyecto_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Proyecto entity.
     *
     * @Route("/{id}/delete", name="proyecto_delete")
     * @Method("post")
     **/
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em= $this->getDoctrine()->getEntityManager();
            $entity = $this->getEntityForDelete('CpmJovenesBundle:Proyecto', $id,$em);

			try{
				$em->remove($entity);
				$em->flush();
				//FIXME eliminar archivos del proyecto
				$this->setSuccessMessage("Proyecto eliminado satisfactoriamente");
			}catch(\PDOException $e){
			    $this->setErrorMessage("No se puede eliminar al proyecto, es muy probable que tenga muchos elementos relacionados. Contactese con el equipo de desarrollo. ({$e->getMessage()})");
			    return $this->redirect($this->generateUrl('proyecto_show', array('id' => $entity->getId())));
			    
			}
	    }
        return $this->redirect($this->generateUrl('proyecto'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /** 
     * Quita un colaborador de un proyecto. No elimina al usuario
     * 
     *  @Route("/remove_colaborador" , name="proyecto_quitar_colaborador")
     * 
     * */
	public function quitarColaboradorDeProyecto() {
		
		$em = $this->getEntityManager();
		
		$id_proyecto = $this->getRequest()->get('id_proyecto');
		$email_colaborador = $this->getRequest()->get('email_colaborador');
		
		$proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id_proyecto,$em);
		
		foreach ($proyecto->getColaboradores() as $colab) { 
			if ($colab->getEmail() == $email_colaborador) { 
				$proyecto->getColaboradores()->removeElement($colab);
				$colab->getColaboraEn()->removeElement($proyecto);
				$em->persist($proyecto);
				$em->persist($colab);
				$em->flush();
				return new Response('success');
			}
		}
		//TODO el reporte de error no es muy completo que digamos ....
		return new Response("error");
		
	}


    /* ***********************************************************************/
    /* ****************** ESTADOS     ****************************************/
    /* ***********************************************************************/
    
    /**
     * Elimina el ultimo estado de un proyecto y fueve al anteriro
     *
     * @Route("/{id}/rollback", name="proyecto_rollback")
     * @Method("get")
     */
    public function deshacerEstado($id) {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id);

        $estadoAnterior = $entity->getEstadoActual();
		$this->getEstadosManager()->deshacerEstadoDeProyecto($entity);    
		if ($entity->getEstadoActual() != $estadoAnterior) 
			$this->setSuccessMessage("Estado deshecho satisfactoriamente.");
		
		return $this->redirect($this->generateUrl('proyecto_show', array('id' => $entity->getId())));
    }


    /**
     * Elimina el ultimo estado de un proyecto y vuelve al anterior
     *
     * @Route("/{id}/cambiar_estado", name="proyecto_cambiar_estado")
     * @Method("post")
     */
     public function cambiarEstado($id) { 
        $proyecto = $this->getEntityForUpdate('CpmJovenesBundle:Proyecto', $id);

		$nuevoEstado = new EstadoProyecto();
		$nuevoEstado->setUsuario($this->getJYM()->getLoggedInUser());
		
        $estadoForm = $this->createForm(new EstadoProyectoType($this->getEstadosManager()), $nuevoEstado);
        $estadoForm->bindRequest($this->getRequest());
        
        
        if ($estadoForm->isValid()) {
        	$file = $estadoForm['archivo']->getData();
        	
	        if ($file) {
				$new_filename = $this->subir_archivo($file,$proyecto);
				$nuevoEstado->setArchivo($new_filename);
	        }
	        $result = $this->getEstadosManager()->cambiarEstadoAProyecto($proyecto,$nuevoEstado);
	        
	        //TODO: enviar email si el estado es aprobado, rehacer, desaprobado o aprobado C        
	        if ($result == "success") { 
	        	$this->setSuccessMessage("El proyecto fue actualizado satisfactoriamente");
	     	}
	        else {
	        	$this->setErrorMessage($result);
	        }
	        
        }
        
        return $this->redirect($this->generateUrl('proyecto_show', array('id' => $proyecto->getId())));
        
    }


	/**
	 * @Route("/{id}/descargar_archivo_viejo", name="proyecto_descargar_archivo_viejo")
     * @Method("get")
     */
    function descargarArchivoEstadoAnterior($id) {
    	$estado = $this->getEntity('CpmJovenesBundle:EstadoProyecto', $id);
        
        if (! ($archivo=$estado->getArchivo())) { 
        	throw $this->createNotFoundException('Estado no posee archivo adjunto');
        }
    	
    	
    	$proyecto_id = $estado->getProyecto()->getId();
    	$file = $this->getUploadDir()."$proyecto_id/".$archivo;
		
		$response = new Response();
		$response->headers->set('Content-Type', 'application/msword');
		$response->headers->set("Content-Disposition", 'Attachment;filename="'.$archivo.'"');
		$response->send();
		readfile($file);
		return $response;
		    	
    }
    
    /* ***********************************************************************/
    /* ****************** COMENTARIOS ****************************************/
    /* ***********************************************************************/
    
    private function crearComentarioBase($id_proyecto, $tipo) {
    	$em = $this->getDoctrine()->getEntityManager();
        $proyecto = $this->getEntity('CpmJovenesBundle:Proyecto', $id_proyecto, $em);
        
    	$asunto = $this->getRequest()->get('asunto');
 		$cuerpo = $this->getRequest()->get('cuerpo');
 		
        $autor = $this->getJYM()->getLoggedInUser();
        
		$comentario = new Comentario($asunto,$cuerpo,$autor,$proyecto,$tipo,false);
 		try{
		 	$em->persist($comentario);
		    $em->flush();    	
			$message = "success";
		}catch(\PDOException $e){
 			$message = "error";
 		}
 		return $message;
    }
    
    /**
	 * @Route("/{id}/crear_postit", name="proyecto_crear_postit")
     * @Method("post")
     */
    public function crearPostIt($id) {
    	$result = $this->crearComentarioBase($id,Comentario::POSTIT);
    	return new Response($result);
 	}
 	
 	/**
	 * @Route("/{id}/crear_tarea", name="proyecto_crear_tarea")
     * @Method("post")
     */
    function crearTarea($id) {
    	$result = $this->crearComentarioBase($id,Comentario::TAREA);
    	return new Response($result);
 	}
 	
 	/**
	 * @Route("/{id}/crear_comentario", name="proyecto_crear_comentario")
     * @Method("post")
     */
    function crearComentario($id) {
    	$result = $this->crearComentarioBase($id,Comentario::COMENTARIO);
    	return new Response($result);
 	}
 	
 	
 	/**
	 * @Route("/{id}/eliminar_comentario", name="proyecto_eliminar_comentario")
     * @Method("post")
     */
 	function eliminarComentario($id) {
		try{
			$em = $this->getDoctrine()->getEntityManager();
          	$comentario = $this->getEntityForUpdate('CpmJovenesBundle:Comentario', $id);
			$em->remove($comentario);
		    $em->flush(); 
			return new Response('success');
		}catch(\Exception $e){	
			return new Response("No se pudo eliminar el comentario. ".$e->getMessage());	
		}		
 	}
 	
 	
 	
 	/**
	 * @Route("/{id}/cambiar_estado_comentario", name="proyecto_cambiar_estado_comentario")
     * @Method("post")
     */
 	function comentarioCambiarEstado($id) {
		try{
			$em = $this->getDoctrine()->getEntityManager();
          	$comentario = $this->getEntityForUpdate('CpmJovenesBundle:Comentario', $id);
			$comentario->setEstado( !$comentario->getEstado() );
			$em->persist($comentario);
		    $em->flush(); 
			return new Response('success');
		}catch(\Exception $e){	
			return new Response($e->getMessage());	
		}	 		
 	}
 	
 	
 	
 	/**
	 * @Route("/search/{search}" , name="proyecto_online_search")
	 * @param $search
	 */
    public function searchAction($search)
    {
    	$em = $this->getEntityManager();
    	$qb = $em->getRepository('CpmJovenesBundle:Proyecto')->createQueryBuilder('p');
		$qb->innerJoin('p.escuela','e');
		$qb->orWhere($qb->expr()->like('e.nombre', ':search'));
		$qb->orWhere($qb->expr()->like('e.numero', ':search'));
		$qb->orWhere($qb->expr()->like('p.titulo', ':search'));
	//	$qb->orWhere($qb->expr()->like('p.deQueSeTrata', ':search'));
		$qb->setParameter('search', '%'.$search.'%');
		$qb->innerJoin('p.ciclo','ciclo');
		$qb->orderBy('ciclo.anio','DESC');
    	$data = $qb->getQuery()->getResult();
    	
    	$proyectos = array();
    	foreach ( $data as $proyecto) {
            $proyectos[] = array(
								'label'=>$proyecto->__toString(), 
								'desc' => "<b>{$proyecto->getTitulo()}</b> {$proyecto->getEscuela()} (ciclo {$proyecto->getCiclo()})",
								'id' => $proyecto->getId(),
								'value' => $proyecto->getId()
								
							   );
        }
    	
    	return $this->createJsonResponse($proyectos);
    }
}
