<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EstadoProyectoType extends AbstractType
{
 	private $estadosManager;
 	
    public function __construct($em){
		$this->estadosManager = $em;
	}
 
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('estado', 'choice', array(
								'required'=>true,
								'choices' => $this->estadosManager->getSelectableEstados(),
								'preferred_choices' => $this->estadosManager->getPreferredChoices(),
								'expanded' => false,
								'label'=> "Nuevo estado"))
            ->add('archivo','file',array('label' => 'Archivo con el proyecto', 
											'required' => false))
            ->add('observaciones',null,array('required'=>false))
            ->add('valoracion','choice',array(
												'required'=>false,
												'choices' => array('Muy bueno'=>'Muy bueno','Bueno' => 'Bueno','Regular' => 'Regular'),
												'expanded' => false,
												'label' => 'Valoración',
												))

        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_estadoproyectotype';
    }
}
