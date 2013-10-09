<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BloqueType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')     
            ->add('horaInicio', 'time', array (
				'label' => 'Hora de Inicio',
				'input'  => 'datetime',
				'widget'  => 'single_text',
				'with_seconds' => false
				))
            ->add('duracion')
            ->add('auditorioDia')
            ->add('tienePresentaciones',null,array('label' => 'Es un bloque con presentaciones'))
            ->add('ejesTematicos',null,array('required'=>false,
            								 'label' => 'Ejes temáticos'
            								   ))
            ->add('areasReferencia',null,array('required'=>false,
            								   'label' => 'Areas de referencia'
            								   ))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_bloquetype';
    }
}
