<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoBatchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {	    	
        $builder
        	->add('proyectos','entity', 
        		array('class'=>'CpmJovenesBundle:Proyecto','multiple'=>true, 'required' =>true, 'label'=>'Proyectos'
        	
        	))
        ;
        //FIXME, poner query_builder,property?
    }

    public function getName()
    {
     	throw new Exception("se esta pidiendo el name de un ProyectoBatchType, algo no esta bien no?");
    }
}