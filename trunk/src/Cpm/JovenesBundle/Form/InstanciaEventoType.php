<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaEventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('descripcion',null,array('label' => 'Descripción', 'required'=>false))
            ->add('url',null,array('label' => 'URL', 'required'=>false))
            ->add('lugar',null,array('label' => 'Lugar', 'required'=>false))
            ->add('fechaInicio',null,array('label' => 'Fecha y Hora de Inicio'))
            ->add('fechaFin',null,array('label' => 'Fecha y Hora de Fin'))
            ->add('fechaCierreInscripcion',null,array('label' => 'Fecha y Hora de Cierre'))
			->add('cerrarInscripcion',null,array('label' => 'Debe cerrarse la inscripcion luego de la fecha de cierre?', 'required'=>false))
            ->add('evento',null,array('label' => 'Evento'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_instanciaeventotype';
    }
}
