<?php


namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionProyectoType extends AbstractType
{
 public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
     	    ->add('temaPrincipal','entity', array('label' => 'Tema Principal',
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
	        ->add('archivo','file',array('label' => 'Archivo con el proyecto'))
       ;
    }
    
    public function getName()
    {
    	return 'cpm_jovenesbundle_presentacionproyectotype';
    }
    
}