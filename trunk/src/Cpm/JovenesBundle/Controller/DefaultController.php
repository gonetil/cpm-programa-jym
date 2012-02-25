<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    
    /**
     * @Route("/hello/{name}", name="_demo_hello")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}
