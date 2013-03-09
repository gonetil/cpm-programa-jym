<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EtapaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('numero', null, array('read_only' => true, 'required'=>false))
            ->add('proyectosVigentesAction')
            ->add('accionesDeUsuario', 'collection', array(
            	'label' => 'Acciones disponibles a usuarios en esta etapa',
			    'type'   => 'text',
			    'options'  => array(
					'required'  => false,
			        'attr'      => array('class' => 'dynamic-form-item-box'),
				), 
				'allow_add' => true,
			    'allow_delete' => true,
			    'prototype' => true,
        		//'by_reference' => false,
			))
            ->add('accionesDeProyecto', 'collection', array(
            	'label' => 'Acciones disponibles a proyectos en esta etapa',
			    'type'   => 'text',
			    'options'  => array(
					'required'  => false,
			        'attr'      => array('class' => 'dynamic-form-item-box'),
				), 
				'allow_add' => true,
			    'allow_delete' => true,
			    'prototype' => true,
        		//'by_reference' => false,
			))
            ->add('deprecated', null,array('required'=>false, 'label' => 'Esta en desuso?'
    		))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_etapatype';
    }
}
