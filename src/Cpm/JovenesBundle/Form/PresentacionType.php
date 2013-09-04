<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('ejeTematico')
            ->add('areaReferencia')
            ->add('tipoPresentacion')
            ->add('bloque')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentaciontype';
    }
}
