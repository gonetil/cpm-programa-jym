<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoBatchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {	    	
        $builder
        	//->add('proyectos','collection',array('allow_add'=>true, 'allow_delete'=>true, 'by_reference'=>false, 'type'=>new ProyectoType())
        	->add('proyectos','entity', array('class'=>'CpmJovenesBundle:Proyecto','multiple'=>true, 'required' =>true, 'label'=>'Proyectos')
        	
        	)
        ;
        //FIXME, poner query_builder,property?
    }

    public function getName()
    {
     	throw new Exception("se esta pidiendo el name de un ProyectoBatchType, algo no esta bien no?");
    }
    
    public function getDefaultOptions(array $options)
    {
    	return array(
            'data_class' => 'Cpm\JovenesBundle\EntityDummy\ProyectoBatch',
    	);
    }
}