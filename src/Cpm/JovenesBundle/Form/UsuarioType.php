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
            ->add('dni')
            ->add('email', 'email')
            ->add('facebookURL',null,array('label'=>'Dirección de Facebook',
            								'required'=>false,
            								'attr'=>array('title'=>'Ingresar dirección de barra de dirección: https://www.facebook.com/usuario...')
            							    )
            								)
            ->add('telefono', null, array('required'=>false))
            ->add('telefonoCelular', null, array('required'=>false))
            ->add('codigoPostal')
            ->add('domicilio')
            ->add('aniosParticipo','hidden',array('label'=>'Años en los que participó'))
	        ->add('localidad','entity',array( 'class' => 'CpmJovenesBundle:Localidad',
	                    									'label'=>'Localidad',
	                    									'attr' => array('class'=>'localidad-selector'),
	             											'query_builder' => function($er) {
																			        return $er->createQueryBuilder('loc')
																			        ->orderBy('loc.nombre', 'ASC');
																			        }
				))
            ->add('distrito','entity',array( 'class' => 'CpmJovenesBundle:Distrito',
            									'label'=>'Distrito',
            									'attr' => array('class'=>'distrito-selector'),
     											'query_builder' => function($er) {
													        return $er->createQueryBuilder('dis')
													            ->orderBy('dis.nombre', 'ASC');
    														}
    											))
            ->add('roles', 'choice', array('choices'=> array('ROLE_USER'=>'Docente','ROLE_ADMIN'=>'Administrador'), 'multiple'=>true))
            ->add('resetPassword', 'checkbox', array('label'=>"Asignar clave?", 'required'=>false))
            ->add('plainPassword', 'repeated', array('required'=>false,'type'=>'password', 'first_name'=> "Clave", 'second_name'=> "Repetir",
            	'invalid_message' => 'Las claves no coinciden'
            ))
        ;
    }


    public function getName()
    {
        return 'cpm_jovenesbundle_usuariotype';
    }


    public function getDefaultOptions(array $options)
    {
    	return array(
                'data_class' => 'Cpm\JovenesBundle\Entity\Usuario',
    	        'groups'  => 'Administracion',
        );
    }
}
