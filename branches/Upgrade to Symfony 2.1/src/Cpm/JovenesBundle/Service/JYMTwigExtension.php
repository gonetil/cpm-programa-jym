<?php
namespace Cpm\JovenesBundle\Service;


class JYMTwigExtension extends \Twig_Extension
{
	protected $jym;
	
    function __construct($jym) {
    	$this->jym = $jym;
    }

    public function getGlobals() {
        return array(
            'jym' => $this->jym
        );
    }

    public function getName()
    {
        return 'jym';
    }

}