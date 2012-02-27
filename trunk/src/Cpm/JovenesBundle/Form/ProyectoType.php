<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProyectoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('nroAlumnos','integer',array('label'  => 'Número de alumnos'))
            ->add('esPrimeraVezDocente',null,array('label' => '¿primera vez del docente?'))
            ->add('esPrimeraVezEscuela',null,array('label' => '¿primera vez de la escuela?'))
            ->add('esPrimeraVezAlumnos',null,array('label' => '¿primera vez de los alumnos?'))
            ->add('escuela')
            ->add('coordinador',null,array('label' => 'Docente Coordinador'))
            ->add('colaboradores')
            ->add('temaPrincipal',null,array('label' => 'Tema Principal'))
            ->add('produccionFinal',null,array('label' => 'Produccion Final'))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_proyectotype';
    }
}
