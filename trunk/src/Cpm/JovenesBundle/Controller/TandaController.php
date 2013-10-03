<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Tanda;
use Cpm\JovenesBundle\Form\TandaType;

use Cpm\JovenesBundle\Entity\Dia;
use Cpm\JovenesBundle\Entity\AuditorioDia;

/**
 * Tanda controller.
 *
 * @Route("/tanda")
 */
class TandaController extends BaseController
{
    /**
     * Lists all Tanda entities.
     *
     * @Route("/", name="tanda")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
		
		$ciclo = $this->getJYM()->getCicloActivo();
		$eventos = $em->getRepository('CpmJovenesBundle:Evento')->findAllQuery($ciclo)->getResult();
        $entities = $em->getRepository('CpmJovenesBundle:Tanda')->findAllQuery()->getResult();
       

        return array('entities' => $entities, 'eventos'=>$eventos);
    }

    /**
     * Finds and displays a Tanda entity.
     *
     * @Route("/{id}/show", name="tanda_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Tanda', $id);
        $deleteForm = $this->createDeleteForm($id);
				
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tanda entity.
     *
     * @Route("/new", name="tanda_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tanda();
        $form   = $this->createForm(new TandaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tanda entity.
     *
     * @Route("/create", name="tanda_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tanda:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Tanda();
        $request = $this->getRequest();
        $form    = $this->createForm(new TandaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tanda_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tanda entity.
     *
     * @Route("/{id}/edit", name="tanda_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Tanda',$id);
        $editForm = $this->createForm(new TandaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tanda entity.
     *
     * @Route("/{id}/update", name="tanda_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Tanda:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Tanda',$id);

        $editForm   = $this->createForm(new TandaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tanda_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tanda entity.
     *
     * @Route("/{id}/delete", name="tanda_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
	        $entity = $this->getEntityForDelete('CpmJovenesBundle:Tanda',$id);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tanda'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    
    
    /**
     * Crea las tandas a partir de las instancias del evento Chapa.
     *
     * @Route("init_tandas", name="tanda_init")
     * @Method("post")
     */
    public function initializeTanda() {
    	$evento_id = $this->getRequest()->get('evento_chapa');
    	$incluir_no_confirmadas = $this->getRequest()->get('incluir_no_confirmadas');
    	
    	$evento = $this->getEntity('CpmJovenesBundle:Evento', $evento_id);
    	if (!$evento) {
    		throw $this->createNotFoundException('Evento no encontrado');
    	}
    	
    	$chapaManager = $this->getChapaManager();
    	try {
    		$msg = $chapaManager->inicializarTandas($evento,$incluir_no_confirmadas);
    		$this->setSuccessMessage("Inicialización de tandas completada. $msg");
    	} catch (\Exception $e) {
    		$this->setErrorMessage('Error al inicializar las tandas de Chapadmalal. Mensaje: '.$e);
            throw $e;
    	}	
    	
		return $this->redirect($this->generateUrl('tanda'));
    }
    
    /**
     * Clona la tanda con id $id_tanda y la setea a la instanciaEvento $instancia_id
     *
     * @Route("clonar_tanda", name="tanda_clonar")
     * 
     */
     public function clonarTanda() {
     	$instancia_id = $this->getRequest()->get('instancia_id');
     	$tanda_id = $this->getRequest()->get('tanda_id');
     	
     	$instanciaEvento = $this->getEntity('CpmJovenesBundle:InstanciaEvento', $instancia_id);
    	if (!$instanciaEvento) {
    		throw $this->createNotFoundException('InstanciaEvento no encontrada');
    	}

     	$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
    	if (!$tanda) {
    		throw $this->createNotFoundException('Tanda no encontrada');
    	}
     	
     	
     	$chapaManager = $this->getChapaManager();
    	try {
    		$chapaManager->clonarTanda($tanda,$instanciaEvento);
    		$this->setSuccessMessage("Tanda clonada satisfactoriamente");
    	} catch (\Exception $e) {
    		$this->setErrorMessage('Error al clonar la tanda seleccioanda. Verifique que la Instancia de evento seleccionada no tenga ya una tanda asignada. \nMensaje de error: '.$e);
            throw $e;
    	}	
    	
		return $this->redirect($this->generateUrl('tanda'));
     	
     	
     }
     

   /**
     * Displays a form to edit an existing Tanda entity.
     *
     * @Route("/{id}/reset", name="tanda_reset")
     * @Template()
     */
    public function resetAction($id) { 
         
        $tanda = $this->getEntity('CpmJovenesBundle:Tanda', $id);
        if (!$tanda) {
    		throw $this->createNotFoundException('Tanda no encontrada');
    	}
    	
      	$chapaManager = $this->getChapaManager();
    	try {
    		$chapaManager->resetTanda($tanda);
    		$this->setSuccessMessage("Tanda reseteada satisfactoriamente");
    	} catch (\Exception $e) {
    		$this->setErrorMessage('Error al resetear la tanda seleccioanda. \nMensaje de error: '.$e);
            throw $e;
    	}	
         
      return $this->redirect($this->generateUrl('tanda_show', array('id' => $tanda->getId() )));
    }
    
    /**
	* ordena dos presentaciones a partir del apellido del docence
	*/
    private function sortByCoordinadorApellido($presentacion1,$presentacion2) {
    	return strcasecmp(	$presentacion1->getApellidoCoordinador() , $presentacion2->getApellidoCoordinador() );
    }

    /**
	* ordena dos presentaciones a partir del dia y bloque en que se presentan
	*/
    private function sortByDiaBloque($presentacion1,$presentacion2) {
    	$bloque1 = $presentacion1->getBloque();
    	$bloque2 = $presentacion2->getBloque();
    	
    	//verifico por si la presentacion aun no tiene un bloque asignado
    	if (!isset($bloque2))
    		return -1;
    	else if (!isset($bloque1))
    		return 1;	
    	
    	
    	$dia1 = $bloque1->getAuditorioDia()->getDia();
    	$dia2 = $bloque2->getAuditorioDia()->getDia();
    	
    	 if ( $dia1->getNumero() < $dia2->getNumero() ) 
    	 	return -1;
    	 else if ( $dia1->getNumero() > $dia2->getNumero() )
    	 	return 1;
    	 else //caen en el mismo dia 
	    	 if ($bloque1->getPosicion() < $bloque2->getPosicion() )
	    	 	return -1;
	    	 else if ($bloque1->getPosicion() > $bloque2->getPosicion() )		
    			return 1;
    			else 
    				return 0;
    }
    
    /**
     * Exporta una tanda a Excel
     *
     * @Route("export_excel", name="tanda_export_excel")
     **/
    
    public function exportarProyectosExcelAction() {
     	$sort_fn = $this->getRequest()->get('sort_fn');
     	if (!isset($sort_fn))
     		$sort_fn = 'sortByCoordinadorApellido';

     	$tanda_id = $this->getRequest()->get('tanda_id');
		$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
        if (!$tanda) 
    		throw $this->createNotFoundException('Tanda no encontrada');
    	
    	$presentaciones = array();
    	foreach ( $tanda->getPresentaciones() as $presentacion )
       		$presentaciones[] = $presentacion;

		
		if (method_exists($this,$sort_fn))
			usort($presentaciones,array($this,$sort_fn));

		$template = 'CpmJovenesBundle:Tanda:export_to_csv.xls.twig';
		return $this->makeExcel(array('presentaciones' => $presentaciones, 'tanda' => $tanda),$template, 'Tanda '.$tanda->getNumero());
    }
    
    
        
    /**
     * Exporta una tanda a Excel
     *
     * @Route("presentacion_cambiar_tanda", name="presentacion_cambiar_tanda")
     **/
    public function moverPresentacionAOtraTanda() {
     	$presentacion_id = $this->getRequest()->get('presentacion_id');
     	$tanda_id = $this->getRequest()->get('tanda_id');
    	
    	$tanda = $this->getEntity('CpmJovenesBundle:Tanda', $tanda_id);
        if (!$tanda) 
    		throw $this->createNotFoundException('Tanda no encontrada');
    		    	
    	try { 
    		$presentacion = $this->getEntity('CpmJovenesBundle:PresentacionInterna',$presentacion_id);
    	} catch (\Exception $e) {
    		$presentacion = $this->getEntity('CpmJovenesBundle:PresentacionExterna',$presentacion_id);    		
    	}

    	$chapaManager = $this->getChapaManager();
    	try {
    		$msg = $chapaManager->cambiarDeTanda($presentacion,$tanda);
    		$this->setSuccessMessage($msg);
    	} catch (\Exception $e) {
    		$this->setErrorMessage('Error al resetear la tanda seleccioanda. \nMensaje de error: '.$e);
            throw $e;
    	}
    	
    	return $this->redirect($this->generateUrl('presentacion_show', array('id' => $presentacion->getId() )));
    }
}
