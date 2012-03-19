<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaEventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('subtitulo',null,array('label' => 'Subtítulo'))
            ->add('descripcion',null,array('label' => 'Descripción'))
            ->add('url',null,array('label' => 'URL'))
            ->add('lugar',null,array('label' => 'Lugar'))
            ->add('fechaInicio',null,array('label' => 'Fecha y Hora de Inicio'))
            ->add('fechaFin',null,array('label' => 'Fecha y Hora de Fin'))
            ->add('evento',null,array('label' => 'Evento'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_instanciaeventotype';
    }
}
