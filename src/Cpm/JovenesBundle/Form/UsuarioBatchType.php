<?php
/*
 * Created on 26/03/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioBatchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {	    	
        $builder
        	->add('usuarios','entity', array('class'=>'CpmJovenesBundle:Usuario','multiple'=>true, 'required' =>true, 'label'=>'Usuarios')
        	)
        ;
    }

    public function getName()
    {
     	throw new \Exception("se esta pidiendo el name de un UsuarioBatchType, algo no esta bien no?");
    }
    
    public function getDefaultOptions(array $options)
    {
    	return array(
            'data_class' => 'Cpm\JovenesBundle\EntityDummy\UsuarioBatch',
    	);
    }
}