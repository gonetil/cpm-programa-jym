<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RegistroUsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('email', 'email')
            ->add('dni')
            ->add('telefono')
            ->add('telefonoCelular', 'text', array('required'=>false))
            ->add('localidad')
            ->add('codigoPostal')
            ->add('clave', 'repeated', array(
				    'type' => 'password',
				    'invalid_message' => 'Las claves deben coincidir',
				    'options' => array('label' => 'Clave'),
				));
            
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_registrousuariotype';
    }
}
