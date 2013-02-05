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
																	        return $er->createQueryBuilder('e')
																	        		  ->innerJoin('e.ciclo','c')
																	        		  ->andWhere('c.activo = 1')
																	        		  ->orderBy('e.titulo', 'ASC');
		    														},
        												'required'=>true
        			))
        ->add('instancia','entity',array(
		            									'label' => 'Instancia',
		            									'class' => 'CpmJovenesBundle:InstanciaEvento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																        return $er->createQueryBuilder('ie')
																        	->innerJoin("ie.evento","e")
																        	->innerJoin("e.ciclo","c")
																        	->andWhere("c.activo = 1")
																        	->orderBy('ie.fechaInicio', 'ASC');
			    														},
        												'required'=>true
    				))
        ->add('ccColaboradores','checkbox',array('required'=>false,'label'=>'Enviar copia a los colaboradores?'))
        ->add('ccEscuelas','checkbox',array('required'=>false,'label'=>'Enviar copia a las escuelas?'))
        ->add('noEnviarCorreo','checkbox',array('required'=>false,'label'=>'No enviar ningún correo'));
        ;
        								
    }
    
    public function getName()
    {
    	return 'cpm_jovenesbundle_invitacionbatchtype';
    }
}
?>