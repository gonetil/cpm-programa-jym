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
            ->add('coordinador',null,array('label' => 'Docente Coordinador'))
            ->add('colaboradores','collection',array('allow_add'=>true, 'by_reference'=>false, 'type'=>new ColaboradorType()))
            ->add('nroAlumnos','integer',array('label'  => 'Número de alumnos', 'attr'=>array('class'=>'number')))
            ->add('esPrimeraVezDocente',null,array('label' => '¿primera vez del docente?', 'required'=>false))
            ->add('esPrimeraVezEscuela',null,array('label' => '¿primera vez de la escuela?', 'required'=>false))
            ->add('esPrimeraVezAlumnos',null,array('label' => '¿primera vez de los alumnos?', 'required'=>false))
            ->add('temaPrincipal',null,array('label' => 'Tema Principal'))
            ->add('produccionFinal',null,array('label' => 'Produccion Final'))
	        ->add('deQueSeTrata',null,array('label'=>'¿De qué se trata el proyecto?'))
	        ->add('motivoRealizacion',null,array('label'=>'¿Para qué se realiza el proyecto?'))
	        ->add('impactoBuscado',null,array('label'=>'¿Cuál es el impacto que se busca?'))
        	->add('escuela', new EscuelaType(), array('label' => 'Datos de la escuela'))
        	
        
        ;
    }
    
    public function getName()
    {
        return 'cpm_jovenesbundle_proyectotype';
    }
}
