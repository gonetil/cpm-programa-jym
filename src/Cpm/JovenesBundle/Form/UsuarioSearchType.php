<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('apellido',null,array('label'=>'Apellido', 'required'=>false))
            ->add('email',null,array('label'=>'Email', 'required'=>false))
        	->add('habilitados','checkbox',array('label'=>'Sólo habilitados', 'required'=>false))
        ;
    }
    public function getName()
    {
    	return 'cpm_jovenesbundle_usuariosearchtype';
    }
}
?>