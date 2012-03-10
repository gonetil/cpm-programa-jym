<?php
namespace Cpm\JovenesBundle\Service;


class JYMTwigExtension extends \Twig_Extension
{
	protected $jym;
    function __construct() {
    }

    public function getGlobals() {
        return array(
            'jym' => JYM::instance()
        );
    }

    public function getName()
    {
        return 'jym';
    }

}