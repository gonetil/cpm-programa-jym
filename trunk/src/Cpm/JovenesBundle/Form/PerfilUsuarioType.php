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
	        ->add('localidad')
            ->add('codigoPostal')
            ->add('telefono')
            ->add('telefonoCelular', 'text', array('required'=>false));

    }


    public function getName()
    {
        return 'cpm_jovenesbundle_perfilusuariotype';
    }
    
    
    
}
