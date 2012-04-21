<?php
namespace Cpm \ JovenesBundle \ Form;

use Symfony \ Component \ Form \ AbstractType;
use Symfony \ Component \ Form \ FormBuilder;

class EscuelaType extends AbstractType {
	public function buildForm(FormBuilder $builder, array $options) {
		
		$builder->add('tipoInstitucion', 'entity', array (
			'class' => 'CpmJovenesBundle:TipoInstitucion',
			'label' => 'Tipo de Institución',
			'empty_value' => 'Otro',
			'required' => false,
			'preferred_choices' => array('Otro')
		))->add('otroTipoInstitucion', null, array (
			'required' => false,
			'label' => 'Otro tipo de institución'
		))->add('tipoEscuela', 'entity', array (
			'label' => 'Tipo de Escuela',
			'class' => 'CpmJovenesBundle:TipoEscuela',
			'query_builder' => function ($er) {
				return $er->createQueryBuilder('t')->where('t.anulado = 0');
			},
			'empty_value' => "Seleccione ...",
		    'preferred_choices' => array("Seleccione ..."),
		            									
		))->add('numero', null, array (
			'label' => 'Número de Escuela',
			'required' => false,
			'data' => '0'
		))->add('nombre', null, array (
			'label' => 'Nombre de la Escuela',
			'required' => false
		))->add('email', null, array (
			'attr' => array (
				'class' => 'email'
			)
		))->add('telefono', null, array (
			'attr' => array (
				'class' => 'number'
			),
			'required' => false,
			'data' => '0'
		))->add('domicilio')->add('codigoPostal', null, array (
			'label' => 'Código postal',
			'attr' => array (
				"minlength" => "4"
			)
		))->add('director')->add('localidad', 'entity', array (
			'class' => 'CpmJovenesBundle:Localidad',
			'label' => 'Localidad',
			'attr' => array (
				'class' => 'localidad-selector'
			),
			'query_builder' => function ($er) {
				return $er->createQueryBuilder('loc')->orderBy('loc.nombre', 'ASC');
			}
		))->add('distrito', 'entity', array (
			'class' => 'CpmJovenesBundle:Distrito',
			'label' => 'Distrito',
			'attr' => array (
				'class' => 'distrito-selector'
			),
			'query_builder' => function ($er) {
				return $er->createQueryBuilder('dis')->orderBy('dis.nombre', 'ASC');
			}
		));
	}

	public function getName() {
		return 'cpm_jovenesbundle_escuelatype';
	}

	public function getDefaultOptions(array $options) {
		return array (
			'data_class' => 'Cpm\JovenesBundle\Entity\Escuela',
			
		);
	}

}