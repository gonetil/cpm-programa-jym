<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CorreoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
//            ->add('destinatario', null, array('required'=>false, "empty_value"=>'Ninguno', 'label'=> "Usuario"))
             ->add('destinatario', 'jquery_entity_autocomplete', array(
																	'required'=>false, 
																	'empty_value'=>'Ninguno', 
																	'label'=> 'Usuario',
																	'class' => 'CpmJovenesBundle:Usuario',
																	'property' => 'id',
																	'url' => 'usuario_online_search',
															)) 
            ->add('email', 'email')
            ->add('asunto')
            ->add('cuerpo')
             ->add('proyecto', 'jquery_entity_autocomplete', array(
																	'label'=> 'Sobre Proyecto',
																	'class' => 'CpmJovenesBundle:Proyecto',
																	'property' => 'id',
																	'url' => 'proyecto_online_search',
															))	
/*            ->add('proyecto', null, array('required'=>false, "empty_value"=>'Ninguno', 'label'=> "Sobre proyecto",
											'query_builder' => function($er) {
																        return $er->createQueryBuilder('p')
																        	->innerJoin("p.ciclo","c")
																        	->andWhere("c.activo = 1")
																        	;
																		}
												)) */
			->add('archivos','collection',array('allow_add'=>true, 'allow_delete'=>true, 'by_reference'=>false, 'type'=>new ArchivoType()))									
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_correotype';
    }
}
