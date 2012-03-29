<?php namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionBatchType extends ProyectoBatchType
{
    public function buildForm(FormBuilder $builder, array $options)
	{
		parent::buildForm($builder, $options);
    	
    	$builder
    	->add('evento','entity',array(
		            									'label' => 'Evento',
		            									'class' => 'CpmJovenesBundle:Evento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('t')->orderBy('t.titulo', 'ASC');
		    														},
        												'required'=>true
        			))
        ->add('instancia','entity',array(
		            									'label' => 'Instancia',
		            									'class' => 'CpmJovenesBundle:InstanciaEvento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																        return $er->createQueryBuilder('t')->orderBy('t.fechaInicio', 'ASC');
			    														},
        												'required'=>true
    				))
        ->add('ccColaboradores','checkbox',array('required'=>false,'label'=>'Enviar copia a los colaboradores?'))
        ->add('ccEscuelas','checkbox',array('required'=>false,'label'=>'Enviar copia a las escuelas?'))
        ;
        								
    }
    
    public function getName()
    {
    	return 'cpm_jovenesbundle_invitacionbatchtype';
    }
}
?>