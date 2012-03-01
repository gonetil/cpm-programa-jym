<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EscuelaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
	        ->add('tipoEscuela', 'entity', array( 'class' => 'CpmJovenesBundle:TipoEscuela','label'=>'Tipo de Escuela'))
        	->add('nombre',null, array('label'=>'Nombre de la Escuela'))
            ->add('email')
            ->add('telefono')
            ->add('domicilio')
            ->add('codigoPostal',null,array('label'=>'Código postal'))
            ->add('director')
	        ->add('tipoInstitucion', 'entity', array( 'class' => 'CpmJovenesBundle:TipoInstitucion','label'=>'Tipo de Institución', 'empty_value'=>'Otro', 'required'=>false))
            ->add('otroTipoInstitucion',null,array('required' => false,'label'=>'Otro tipo de institución'))
            ->add('localidad', 'entity', array( 'class' => 'CpmJovenesBundle:Localidad','label'=>'Localidad', 'attr' => array('class'=>'localidad-selector')))	    
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_escuelatype';
    }
    
    public function getDefaultOptions(array $options)
    {
    	return array(
                    'data_class' => 'Cpm\JovenesBundle\Entity\Escuela',
    	);
    }
    
}
