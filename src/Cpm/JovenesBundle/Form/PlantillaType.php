<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlantillaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('asunto')
            ->add('cuerpo')
            ->add('puedeBorrarse',null,array('required'=>false, 
            								 'label'=>'Puede borrarse?'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_plantillatype';
    }
}
