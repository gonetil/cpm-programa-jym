<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ConfirmacionCamposChapaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
	        ->add('titulo')
            ->add('temaPrincipal','entity',
            					array('label' => 'Tema Principal',
            						  'class' => 'CpmJovenesBundle:Tema',
            						  'query_builder' => function($er) { return $er->createQueryBuilder('t')->where('t.anulado = 0');}
    								    ))
            ->add('produccionFinal','entity',
            					array('label' => 'Producción Final',
            						  'class' => 'CpmJovenesBundle:Produccion',
            						  'query_builder' => function($er) { return $er->createQueryBuilder('p')->where('p.anulado = 0');}
    								    ))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_confirmacion_campos_chapa';
    }
    
    public function getDefaultOptions(array $options)
    {
    	return array(
                'data_class' => 'Cpm\JovenesBundle\EntityDummy\ConfirmacionCamposChapa'
        );
    }
    
}

