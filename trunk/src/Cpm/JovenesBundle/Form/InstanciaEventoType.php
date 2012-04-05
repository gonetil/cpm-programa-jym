<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaEventoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('evento',null,array('label' => 'Evento'))
            ->add('descripcion',null,array('label' => 'Descripción', 'required'=>false))
            ->add('url',null,array('label' => 'URL', 'required'=>false))
            ->add('lugar',null,array('label' => 'Lugar', 'required'=>false))
            ->add('cerrarInscripcion',null,array('label' => 'Debe bloquearse la inscripcion luego de la fecha de fin de inscripción?', 'required'=>false))
            ->add('fechaCierreInscripcion',null,array('label' => 'Fin de inscripción (Fecha y Hora)', 'date_widget'=>'single_text', 'time_widget'=>'single_text'))
		    ->add('fechaInicio',null,array('label' => 'Fecha y Hora de Inicio del evento', 'date_widget'=>'single_text', 'time_widget'=>'single_text'))
            ->add('fechaFin',null,array('label' => 'Fecha y Hora de Fin del evento', 'date_widget'=>'single_text', 'time_widget'=>'single_text'))         	
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_instanciaeventotype';
    }
}
