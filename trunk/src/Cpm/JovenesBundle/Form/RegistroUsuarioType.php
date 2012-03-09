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
        $builder
        	->add('distrito','entity',array( 'class' => 'CpmJovenesBundle:Distrito',
            									'label'=>'Distrito', 
            									'attr' => array('class'=>'distrito-selector'),
     											'query_builder' => function($er) {
													        return $er->createQueryBuilder('dis')
													            ->orderBy('dis.nombre', 'ASC');
    														}
    											))
        	->add('localidad',null,array('attr'=>array('class'=>'localidad-selector')))
            ->add('nombre')
            ->add('apellido')
            ->add('dni', 'number')
    		->add('email', 'email')
            ->add('codigoPostal',null,array('label'=>'Código Postal'))
            ->add('telefono',null,array('label'=>'Teléfono'))
            ->add('telefonoCelular', 'text', array('required'=>false, 'label'=>'Teléfono Celular'))
            ->add('plainPassword', 'repeated', array('type' => 'password', 'first_name'=>'Clave', 'second_name'=>'Repetir Clave'))
        
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
