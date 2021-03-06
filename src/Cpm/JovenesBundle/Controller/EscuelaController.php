<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Escuela;
use Cpm\JovenesBundle\Form\EscuelaType;

use Cpm\JovenesBundle\Filter\EscuelaFilter;
use Cpm\JovenesBundle\Filter\EscuelaFilterForm;
/**
 * Escuela controller.
 *
 * @Route("/escuela")
 */
class EscuelaController extends BaseController
{
    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="escuela")
     * @Template()
     */
    public function indexAction()
    {
        return $this->filterAction(new EscuelaFilter(), 'escuela');
    }

    /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/{id}/show", name="escuela_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntity('CpmJovenesBundle:Escuela', $id);
        return array('entity'  => $entity,);
    }

    /**
     * Displays a form to edit an existing Escuela entity.
     *
     * @Route("/{id}/edit", name="escuela_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Escuela', $id);
        $editForm = $this->createForm(new EscuelaType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Escuela entity.
     *
     * @Route("/{id}/update", name="escuela_update")
     * @Method("post")
     * @Template("CpmJovenesBundle:Escuela:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getEntityForUpdate('CpmJovenesBundle:Escuela', $id, $em);

        $editForm   = $this->createForm(new EscuelaType(), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->setSuccessMessage("Institucion modificada satisfactoriamente");
            return $this->redirect($this->generateUrl('escuela_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

}
