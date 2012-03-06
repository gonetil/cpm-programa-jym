<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistroUsuarioType extends BaseType
{
	public function __construct($c){
		parent::__construct($c);
	}
	
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('dni', 'number')
            ->add('localidad')
            ->add('codigoPostal')
            ->add('telefono')
            ->add('telefonoCelular', 'text', array('required'=>false))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_registrousuariotype';
    }
    
    
    public function getDefaultOptions(array $options)
    {
    	$a = parent::getDefaultOptions($options);
    	//$a['groups'] = 'Registration';
        return $a;
    }
    
}
