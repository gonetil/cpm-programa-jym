<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProduccionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('anulado',null,array('required' => false))
            ->add('tipoPresentacion',null,array('required' => false))
            ->add('duracionEstimada',null,array('required' => true , 'label'=> 'Duración estimada para las presentaciones de este tipo' ))
            ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_producciontype';
    }
}
