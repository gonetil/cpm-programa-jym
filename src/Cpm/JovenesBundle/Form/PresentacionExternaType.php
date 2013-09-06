<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionExternaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('escuela')
            ->add('provincia')
            ->add('localidad')
            ->add('ejeTematico')
            ->add('areaReferencia')
            ->add('tipoPresentacion')
            ->add('bloque',null,array('required'=>false))
            ->add('tanda')
            ->add('apellido_coordinador')
            ->add('nombre_coordinador')
            ->add('personas_confirmadas')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentacionexternatype';
    }
}
