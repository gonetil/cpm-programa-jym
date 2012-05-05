<?php


namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ColaboradoresProyectoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('colaboradores','collection',array('allow_add'=>true, 'allow_delete'=>true, 'by_reference'=>false, 'type'=>new ColaboradorType()))
	;
    }
     
    public function getName()
    {
    	return 'cpm_jovenesbundle_proyectotype';
    }
}   