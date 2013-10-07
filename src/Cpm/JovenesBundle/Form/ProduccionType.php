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
            ->add('tipoPresentacion',null,array('required' => true , 'label'=>'Tipo de presentacion asociada/soporte (sera utilizado luego para la diagramación de las tandas de CHAPA). Ej: video'))
            ->add('duracionEstimada',null,array('required' => true , 'label'=> 'Duración estimada para las presentaciones de este tipo' ))
            ->add('anulado',null,array('required' => false, 'label'=>'Anulada?'))
            ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_producciontype';
    }
}
