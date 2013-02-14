<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('apellido',null,array('label'=>'Apellido', 'required'=>false))
            ->add('email',null,array('label'=>'Email', 'required'=>false))
        	->add('habilitados','choice',array('label'=>'Habilitados',
        		  								'choices' => array(1=>"Si",2=>"No"),
                    							'preferred_choices' => array("Todos"),
                								'empty_value' => "Todos",
                								'expanded'=>false,
                								'required'=>false
				))
        	->add('coordinadores','checkbox',array('label'=>'Sólo coordinadores', 'required'=>false))
        	->add('ciclo','entity',array('label' => 'Ciclo',
		    							'class' => 'CpmJovenesBundle:Ciclo',
		    							'query_builder' => function($er) {
		    														return $er->createQueryBuilder('c')
		    																  ->orderBy('c.activo', 'DESC')
		    																  ->orderBy('c.titulo', 'ASC');;
		    														},
		    							'empty_value' => "Cualquiera",
		    							'preferred_choices' => array("Cualquiera"),
		    							'required'=>false
		    					)) 
		  		
        ;
    }
    public function getName()
    {
    	return 'cpm_jovenesbundle_usuariosearchtype';
    }
}
?>