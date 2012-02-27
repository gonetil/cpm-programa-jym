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
            ->add('nroAlumnos')
            ->add('esPrimeraVezDocente')
            ->add('esPrimeraVezEscuela')
            ->add('esPrimeraVezAlumnos')
            ->add('escuela')
            ->add('coordinador')
            ->add('colaboradores')
            ->add('temaPrincipal')
            ->add('produccionFinal')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_proyectotype';
    }
}
