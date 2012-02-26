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
//            ->add('ultimoAcceso', datearray('required'=>'false'))
            ->add('dni')
            ->add('telefono', 'text', array('required'=>false))
            ->add('telefonoCelular', 'text', array('required'=>false))
            ->add('email', 'email')
            ->add('codigoPostal')
            ->add('estaHabilitado')
            ->add('esAdmin', 'checkbox', array('required'=>false))
            ->add('localidad')
            ->add('distrito')
            ->add('region')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }
}
