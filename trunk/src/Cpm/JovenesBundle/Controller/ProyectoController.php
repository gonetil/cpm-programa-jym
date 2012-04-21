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

use Cpm\JovenesBundle\Filter\ProyectoFilter;
use Cpm\JovenesBundle\Filter\ProyectoFilterForm;

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
//    	/$ciclo = $this->getJYM()->getCicloActivo();
        
		return $this->filterAction(new ProyectoFilter(), 'proyecto', $stats);
    	
    }

    private function getSystemStats() {
    	$stats = array(); 
    	$em = $this->getEntityManager();
    	$repo = $em->getRepository('CpmJovenesBundle:Proyecto');
    	
    	$qb = $repo->createQueryBuilder('p');
    	$stats['total_proyectos'] = $qb->select($qb->expr()->count('p'))->getQuery()->getSingleScalarResult();

    	$stats['total_PrimeraVezDocente'] = $qb->select($qb->expr()->count('p'))->where('p.esPrimeraVezDocente = 1')->getQuery()->getSingleScalarResult();
    	$stats['total_PrimeraVezAlumnos'] = $qb->select($qb->expr()->count('p'))->where('p.esPrimeraVezAlumnos = 1')->getQuery()->getSingleScalarResult();
    	$stats['total_PrimeraVezEscuela'] = $qb->select($qb->expr()->count('p'))->where('p.esPrimeraVezEscuela = 1')->getQuery()->getSingleScalarResult();
    	 
    	$stats['total_Coordinadores'] = count($qb->select($qb->expr()->count('p'))->groupBy('p.coordinador')->getQuery()->getResult());    	
    	
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

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyecto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
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
        $entity->setEstado(Proyecto::__ESTADO_INICIADO);
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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyecto entity.');
        }

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

        $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);
        
        $colabs = array(); //colaboradores que tenia la entidad
        foreach ($entity->getColaboradores() as $c) {
        	$colabs[] = $c->getEmail();
        }
        
        if (!$entity) 
        {
            throw $this->createNotFoundException('No se encontro el Proyecto');
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
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CpmJovenesBundle:Proyecto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Proyecto entity.');
            }
	
			try{
				$em->remove($entity);
				$em->flush();
				$this->setSuccessMessage("Proyecto eliminado satisfactoriamente");
				return $this->redirect($this->generateUrl('proyecto'));
			}catch(\PDOException $e){
			    $this->setErrorMessage("No se puede eliminar al proyecto, es muy probable que tenga muchos elementos relacionados. Contactese con el equipo de desarrollo.");
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
		$id_proyecto = $this->getRequest()->get('id_proyecto');
		$email_colaborador = $this->getRequest()->get('email_colaborador');
		 
		$proyecto = $this->getRepository('CpmJovenesBundle:Proyecto')->findOneById($id_proyecto);

		$em = $this->getEntityManager();
		
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


		return new Response("error");
		
	}

    
}
