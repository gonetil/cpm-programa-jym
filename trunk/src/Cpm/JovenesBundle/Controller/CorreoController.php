<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cpm\JovenesBundle\Entity\Correo;
use Cpm\JovenesBundle\Form\CorreoType;

/**
 * Correo controller.
 *
 * @Route("/correo")
 */
class CorreoController extends Controller
{
    /**
     * Lists all Correo entities.
     *
     * @Route("/", name="correo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Correo')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Correo entity.
     *
     * @Route("/{id}/show", name="correo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CpmJovenesBundle:Correo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        return array(
            'entity'      => $entity);
    }

    /**
     * Displays a form to create a new Correo entity.
     *
     * @Route("/new", name="correo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Correo();
        $form   = $this->createForm(new CorreoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Correo entity.
     *
     * @Route("/create", name="correo_create")
     * @Method("post")
     * @Template("CpmJovenesBundle:Correo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Correo();
        $request = $this->getRequest();
        $form    = $this->createForm(new CorreoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('correo_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

}
