<?php
namespace Cpm \ JovenesBundle \ Filter;

use Symfony \ Component \ Form \ AbstractType;
use Symfony \ Component \ Form \ FormBuilder;

class CorreoFilterForm extends AbstractType {
	public function buildForm(FormBuilder $builder, array $options) {
		$builder->add('fechaMin', 'date', array (
			'label' => 'Fecha Min',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT,
		))->add('fechaMax', 'date', array (
			'label' => 'Fecha Max',
			'widget' => 'single_text',
			'required' => false,
			'attr' => array ('class' => 'datepicker'),
			'format' => \AppKernel::DATE_FORMAT
		))->add('destinatario', 'entity', array (
			'label' => 'Destinatario',
			'class' => 'CpmJovenesBundle:Usuario',
			'empty_value' => "Todos",
			'required' => false
		))->add('email', 'email', array (
			'required' => false
		))->add('asunto', null, array (
			'required' => false
		))->add('proyecto', 'entity', array (
			'label' => 'Proyecto',
			'class' => 'CpmJovenesBundle:Proyecto',
			'empty_value' => "Todos",
			'required' => false
		))
		//->add('cuerpo')
		//->add('sort', null)
		;
	}

	public function getName() {
		return 'cpm_jovenesbundle_correotype';
	}
}