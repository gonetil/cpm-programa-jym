<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EscuelaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('tipoEscuela')
        	->add('nombre')
            ->add('email')
            ->add('telefono')
            ->add('domicilio')
            ->add('codigoPostal')
            ->add('director')
            ->add('tipoInstitucion')
            ->add('otroTipoInstitucion',null,array('required' => false))
            ->add('localidad')
	        ->add('deQueSeTrata')
	        ->add('motivoRealizacion')
	        ->add('impactoBuscado')
	        
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_escuelatype';
    }
}
