<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Cpm\JovenesBundle\Service\EstadosManager;

class EstadoFilterForm extends ModelFilterForm
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
           ->add('yaEvaluado', 'choice' ,array('label' => 'Evaluado' , 
            											'choices' => array(1=>"Evaluado",2=>"Sin evaluar"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
            ->add('conArchivo', 'choice' ,array('label' => 'Archivo cargado' , 
            											'choices' => array(1=>"Con archivo",2=>"Sin archivo"),
            											'preferred_choices' => array("Todos"),
        												'empty_value' => "Todos",
        												'expanded'=>false,
        												'required'=>false
                										))
            ->add('vigente', 'choice' ,array('label' => 'Proyecto vigente' , 
            											'choices' => array(1=>"Si",2=>"No",3=>"Todos"),
            											'data' => '1',
        												'empty_value' => false,
        												'expanded'=>false,
        												'required'=>false
                										))
			->add('nota','choice',array('label' => 'Nota' ,
										  'choices' => EstadosManager::getEstadosEvaluados() + array(ESTADO_APROBADO_Y_APROBADO_C => "Aprobados y Aprobados C"),
										  'empty_value'=>'Todas',
										  'expanded'=>false,
										  'required' => false
										  ))                										
                										;     
    }          	
}