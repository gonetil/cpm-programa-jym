<?php namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionMasivaType extends CorreoMasivoType
{
    public function buildForm(FormBuilder $builder, array $options)
	{
				$builder->add('evento','entity',array(
		            									'label' => 'Evento',
		            									'class' => 'CpmJovenesBundle:Evento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('t')
															            ->orderBy('t.titulo', 'ASC');
		    														},
        												'required'=>false
        								))
        			->add('instancia','entity',array(
		            									'label' => 'Instancia',
		            									'class' => 'CpmJovenesBundle:InstanciaEvento',
		    								    		'empty_value' => "Todos",
		    								            'preferred_choices' => array("Todos"),
		            									'query_builder' => function($er) {
																	        return $er->createQueryBuilder('t')
															            ->orderBy('t.subtitulo', 'ASC');
		    														},
        												'required'=>false
        								))
        								
    }
    
    public function getName()
    {
    	return 'cpm_jovenesbundle_invitacionmasivatype';
    }
}
?>