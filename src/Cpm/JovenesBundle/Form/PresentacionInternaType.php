<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionInternaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('ejeTematico')
            ->add('areaReferencia')
            ->add('tipoPresentacion')
            ->add('proyecto')
            ->add('distrito')
            ->add('localidad')
            ->add('bloque',null,array('required'=>false))
            ->add('tanda')
            
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentacioninternatype';
    }
}
