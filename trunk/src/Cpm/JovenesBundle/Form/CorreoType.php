<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CorreoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('destinatario', null, array('required'=>false, "empty_value"=>'Ninguno', 'label'=> "Usuario"))
            ->add('email', 'email')
            ->add('asunto')
            ->add('cuerpo')
            ->add('proyecto', null, array('required'=>false, 
										  "empty_value"=>'Ninguno', 
											'label'=> "Sobre proyecto",
											'query_builder' => function($er) {
																        return $er->createQueryBuilder('p')
																        	->innerJoin("p.ciclo","c")
																        	->andWhere("c.activo = 1")
																        	;
																		}
												))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_correotype';
    }
}
