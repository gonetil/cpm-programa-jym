<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionType extends InvitacionUsuarioType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	
        $builder
            //->add('fechaCreacion',null,array('label' => 'Fecha de Creación'))
            ->add('asistio',null,array('label' => '¿Asistió?', 'required'=>false))
            ->add('proyecto',null,array('label' => 'Proyecto', 'read_only' =>true))
            ->add('instanciaEvento',null,array('label' => 'Instancia de evento', 'read_only' =>true))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
