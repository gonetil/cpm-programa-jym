<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ FormBuilder;

class CorreoFilterForm extends ModelFilterForm {

		
	public function buildForm(FormBuilder $builder, array $options) {
		$builder->add('fechaMin', 'date', array (
			'label' => 'Fecha Desde',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT,
		))->add('fechaMax', 'date', array (
			'label' => 'Fecha Hasta',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT
		))
		 ->add('destinatario', 'jquery_entity_autocomplete', array(
																	'required'=>false, 
																	'empty_value'=>'Todos', 
																	'label'=> 'Destinatario',
																	'class' => 'CpmJovenesBundle:Usuario',
																	'property' => 'id',
																	'url' => 'usuario_online_search',
															))
		/*->add('destinatario', 'entity', array (
			'label' => 'Destinatario',
			'class' => 'CpmJovenesBundle:Usuario',
			'empty_value' => "Todos",
			'required' => false
		))*/
		->add('email', 'email', array (
			'required' => false
		))->add('asunto', null, array (
			'required' => false
		))
		
		/*->add('proyecto', 'entity', array (
			'label' => 'Proyecto',
			'class' => 'CpmJovenesBundle:Proyecto',
			'empty_value' => "Todos",
			'required' => false
		)) */
		
		//->add('cuerpo')
		;
	}
}