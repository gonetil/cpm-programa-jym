<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Presentacion;
use Cpm\JovenesBundle\Form\PresentacionType;

/**
 * Presentacion controller.
 *
 * @Route("/presentacion")
 */
class PresentacionController extends BaseController
{
    /**
     * Lists all Presentacion entities.
     *
     * @Route("/", name="presentacion")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->getRepository('CpmJovenesBundle:Presentacion')->findAllQuery();
        return $this->paginate($entities);
    }
    /**
     * Finds and displays a Presentacion entity.
     *
     * @Route("/{id}/show", name="presentacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Presentacion', $id);
		$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),  
		);
    }

    /**
     * Displays a form to edit an existing Presentacion entity.
     *
     * @Route("/{id}/edit", name="presentacion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $presentacion= $this->getEntityForUpdate('CpmJovenesBundle:Presentacion', $id);
        if ($presentacion->esExterna()) {
			return $this->forward("CpmJovenesBundle:PresentacionExterna:edit", array('id'=>$id));
        }else{
    		return $this->forward("CpmJovenesBundle:PresentacionInterna:edit", array('id'=>$id));
        }
    }

    /**
     * Deletes a Presentacion entity.
     *
     * @Route("/{id}/delete", name="presentacion_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $presentacion = $this->getEntityForDelete('CpmJovenesBundle:Presentacion', $id);

            $em = $this->getDoctrine()->getEntityManager();
            try{
            	$bloque=$presentacion->getBloque();
				if (!empty($bloque)){
					$bloque->removePresentacion($presentacion);
	          		$em->persist($bloque);
				}
				
				$tanda=$presentacion->getTanda();
				$tanda->removePresentacion($presentacion);
	          	$em->persist($tanda);
	            
            	$em->remove($presentacion);
            	$em->flush();
            } catch(\Exception $e ) {
				$this->setErrorMessage("Error al tratar de borrar la presentacion. Mensage: ".$e->getMessage());
			}
        }

        return $this->redirect($this->generateUrl('presentacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
