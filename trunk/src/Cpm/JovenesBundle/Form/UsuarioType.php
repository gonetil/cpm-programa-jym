<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('clave')
            ->add('dni')
	        ->add('nombre')
	        ->add('apellido')
            ->add('telefono', 'text', array('required'=>false))
            ->add('telefonoCelular', 'text', array('required'=>false))
            ->add('email', 'email',array('attr'=>array('class'=>'number')))
            ->add('codigoPostal')
            ->add('estaHabilitado')
            ->add('esAdmin', 'checkbox', array('required'=>false))
            ->add('localidad')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }
}
