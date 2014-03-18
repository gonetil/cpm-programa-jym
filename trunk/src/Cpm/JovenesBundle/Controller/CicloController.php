<?php

namespace Cpm\JovenesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Ciclo;
use Cpm\JovenesBundle\Form\CicloType;

/**
 * Ciclo controller.
 *
 * @Route("/ciclo")
 */
class CicloController extends BaseController
{
    /**
     * Lists all Ciclo entities.
     *
     * @Route("/", name="ciclo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Ciclo')->findAll();
        
        if(!$this->getJYM()->isUserGranted("ROLE_SUPER_ADMIN")){
			$this->setInfoMessage('No tiene permisos suficientes para modificar los ciclos y etapas');
        }
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Ciclo entity.
     *
     * @Route("/{id}/show", name="ciclo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Ciclo', $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Ciclo entity.
     *
     * @Route("/new", name="ciclo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ciclo();
        $form   = $this->createForm(new CicloType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Ciclo entity.
     *
     * @Route("/create", name="ciclo_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Ciclo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Ciclo( $this->getJYM()->getEtapaInicial() );
        $request = $this->getRequest();
        $form    = $this->createForm(new CicloType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            try{
            	$em = $this->getDoctrine()->getEntityManager();
            	$em->persist($entity);
            	$em->flush();
				return $this->redirect($this->generateUrl('ciclo_show', array('id' => $entity->getId())));
			}catch(\PDOException $e){
			    $this->setErrorMessage("No se pudo crear el ciclo, compruebe que no exista un ciclo del mismo Año  ({$e->getMessage()})");
			}
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Ciclo entity.
     *
     * @Route("/{id}/edit", name="ciclo_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Ciclo', $id);
        $editForm = $this->createForm(new CicloType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ciclo entity.
     *
     * @Route("/{id}/update", name="ciclo_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Ciclo:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Ciclo', $id, $em);

        $editForm   = $this->createForm(new CicloType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
			try{
	            $em->persist($entity);
	            $em->flush();
	            return $this->redirect($this->generateUrl('ciclo_show', array('id' => $id)));
			}catch(\PDOException $e){
			    $this->setErrorMessage("No se pudo modificar el ciclo, compruebe que no exista otro ciclo del mismo Año  ({$e->getMessage()})");
			}
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ciclo entity.
     *
     * @Route("/{id}/delete", name="ciclo_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
	    	$entity = $this->getEntityForDelete('CpmJovenesBundle:Ciclo', $id, $em);

            //FIXME que pasa si se esta eliminando un ciclo con proyectos?
            if ($entity->getActivo()){
            	$this->setErrorMessage("No se puede eliminar un ciclo activo");	
            }else{
	            $em->remove($entity);
	            $em->flush();
	        }
        }

        return $this->redirect($this->generateUrl('ciclo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    /**
     *
     * @Route("/{id}/activate", name="ciclo_activate")
     * @Template()
     */
    public function activateAction($id)
    {
    	$entity = $this->getEntityForUpdate('CpmJovenesBundle:Ciclo', $id);
    	
    	$jym = $this->getJYM();
    	$cicloActivo = $jym->getCicloActivo();
    	if ($cicloActivo->getId() == $id){
    		$this->setInfoMessage("El ciclo ya se encuetra activo");
    	}else{
    		$jym->setCicloActivo($entity);
    		$this->setSuccessMessage("El ciclo fue activado correctamente");
    	}
    	return $this->redirect($this->generateUrl('ciclo'));
    }

 	/**
     * @Route("/goto-siguiente-etapa", name="goto_siguiente_etapa")
     * @Template()
     */
    public function gotoSiguienteEtapaAction()
    {
    	$jym = $this->getJYM();
		$nuevaEtapa = $jym->getEtapaSiguiente();
		if (empty($nuevaEtapa))
			throw new \OutOfRangeException("No existe una etapa siguiente a la actual");

		return $this->gotoEtapa($nuevaEtapa);
    }
 	
 	/**
     * @Route("/goto-etapa-anterior", name="goto_etapa_anterior")
     */
    public function gotoEtapaAnteriorAction()
    {
    	$jym = $this->getJYM();
		$nuevaEtapa = $jym->getEtapaAnterior();
		if (empty($nuevaEtapa))
			throw new \OutOfRangeException("No existe una etapa posterior a la actual");

		return $this->gotoEtapa($nuevaEtapa);
    }
    private function gotoEtapa($nuevaEtapa){
    	$jym = $this->getJYM();

		$c = $jym->getCicloActivo();
		$jym->puedeEditar($c, true);
		$c->setEtapaActual($nuevaEtapa);
		
		try{
			$e = new \Exception();
			$this->get('logger')->warn("Algo raro pasa, se modifca la etapa del ciclo: ".$e->getTraceAsString());
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($c);
			$em->flush();
			
			$this->setSuccessMessage("Se paso a la etapa ".$nuevaEtapa->getNumero());
		}catch(\Exception $e){
			$this->setErrorMessage("No se pudo pasar a la etapa ".$nuevaEtapa->getNumero()." se produjo el siguiente error: ".($e->getMessage()));
		}
    	return $this->redirect($this->generateUrl('ciclo'));
    }
    


}
