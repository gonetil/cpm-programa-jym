<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('apellido')
            ->add('nombre')
     //       ->add('username')
            ->add('dni', 'number')
            ->add('email', 'email')
            ->add('telefono')
            ->add('telefonoCelular')
            ->add('codigoPostal')
            ->add('localidad')
            ->add('roles', 'choice', array('choices'=> array('ROLE_USER'=>'Docente','ROLE_ADMIN'=>'Administrador'), 'multiple'=>true))
            ->add('enabled')
            ->add('password', 'repeated', array('type'=>'password', 'first_name'=> "Clave", 'second_name'=> "Repetir Clave"))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }
}
