<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class PerfilUsuarioType extends BaseType
{
	public function __construct($c){
		parent::__construct($c);
	}


    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);

		$builder->get('user')->setAttribute('label', 'Revise sus datos y luego ingrese su clave para confirmar');
    	$builder->get('current')->setAttribute('label', 'Clave');
    }
    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilder $builder
     * @param array       $options
     */
    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('dni', 'number')
    	    	->add('email', 'email')
            ->add('distrito','entity',array( 'class' => 'CpmJovenesBundle:Distrito',
            									'label'=>'Distrito',
            									'attr' => array('class'=>'distrito-selector'),
     											'query_builder' => function($er) {
													        return $er->createQueryBuilder('dis')
													            ->orderBy('dis.nombre', 'ASC');
    														}
    											))
    		->add('localidad',null,array('attr'=>array('class'=>'localidad-selector')))
    		->add('domicilio',null,array('label'=>'Domicilio','required'=>true))
            ->add('codigoPostal',null,array('label'=>'Código Postal'))
            ->add('telefono',null,array('label'=>'Teléfono'))
            ->add('telefonoCelular', 'text', array('required'=>false,
    												'label' => 'Teléfono Celular',
													  'attr' => array( 'placeholder' => 'Ej. 0221156437325',
																						 'hint' => 'Ej. (0221) 15-6437325'
																					 )
																			)
									)
    		//->add('aniosParticipo','hidden',array('label'=>'Años en los que participó'))
    												;

    }


    public function getName()
    {
        return 'cpm_jovenesbundle_perfilusuariotype';
    }



}
