<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Perfil controller.
 *
 * @Route("/")
 */
class PerfilController extends Controller
{
    /**
     * Lists all Proyecto entities.
     */
    public function indexAction()
    {
        return array();
    }

}
