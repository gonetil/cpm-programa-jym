<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ColaboradorType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
	        ->add('nombre')
	        ->add('apellido')
            ->add('email', 'email')
/*            ->add('facebookURL',null,array('label'=>'Dirección de Facebook',
            								'required'=>false,
            								'attr'=>array('title'=>'Ingresar dirección de barra de dirección: https://www.facebook.com/usuario...')
            							    )
            								)
                            */
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_colaboradortype';
    }

    public function getDefaultOptions(array $options)
    {
    	return array(
                'data_class' => 'Cpm\JovenesBundle\Entity\Usuario',
    	        'groups'  => 'Colaborador',
        );
    }

}
