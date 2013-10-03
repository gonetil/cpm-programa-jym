<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionInternaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	$presentacion = $options['data'];
    	$isNew = $presentacion->getId() <1; 
 
        if ($isNew){
        	$builder->add('tanda');
			$builder->add('invitacion');
        }
        else{
        	$builder->add('bloque',null,array(	'required'=>false,
            							'query_builder' => function ($bl) {
											return $bl->createQueryBuilder('bc')->where('bc.tienePresentaciones = true');
			}));
        }   
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentacioninternatype';
    }
}
