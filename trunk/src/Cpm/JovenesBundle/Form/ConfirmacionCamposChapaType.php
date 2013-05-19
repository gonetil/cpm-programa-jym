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
     	    ->add('temaPrincipal','entity', array('label' => 'Eje temático',
                    						  'class' => 'CpmJovenesBundle:Tema',
                    						  'query_builder' => function($er) { 
        															return $er->createQueryBuilder('t')->where('t.anulado = 0');
        														 }
        	))
        	->add('produccionFinal','entity', array('label' => 'Producción Final',
                    						  'class' => 'CpmJovenesBundle:Produccion',
                    						  'query_builder' => function($er) { 
				        												return $er->createQueryBuilder('p')->where('p.anulado = 0');
				        										}
        		))
	        ->add('deQueSeTrata',null,array('label'=>'Breve descripción'))
	        ->add('eje','entity',
            					array('label' => 'Área de referencia',
            						  	'empty_value' => "Seleccione ...",
            						  	'required'=>true,
									    'preferred_choices' => array("Seleccione ..."),
    	        						'class' => 'CpmJovenesBundle:Eje',
            						  	'query_builder' => function($er) { return $er->createQueryBuilder('e')->where('e.anulado = 0');}
    								  ))
			->add('escuela', new EscuelaType(), array('label' => 'Datos de la escuela'))  
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentacionproyectotype'; //'cpm_jovenesbundle_confirmacion_campos_chapa';
    }
    
    
}

