<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EjeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('ejesTematicos',null,array('label'=>'Ejes temáticos', 'required'=>false,'attr'=>array('class' => 'large_select' )))
            ->add('anulado')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_ejetype';
    }
}
