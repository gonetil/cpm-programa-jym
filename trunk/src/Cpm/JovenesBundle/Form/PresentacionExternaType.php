<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PresentacionExternaType extends AbstractType
{
	private $estadosManager;
 	
    public function __construct($em){
		$this->estadosManager = $em;
	}
	
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('escuela')
            ->add('provincia')
            ->add('localidad')
            ->add('ejeTematico')
            ->add('areaReferencia')
            ->add('tipoPresentacion')
            ->add('bloque',null,array(	'required'=>false,
            							'query_builder' => function ($bl) {
											return $bl->createQueryBuilder('bc')->where('bc.tienePresentaciones = true');
			}))
            ->add('tanda')
            ->add('apellido_coordinador')
            ->add('nombre_coordinador')
            ->add('personas_confirmadas')
  			->add('valoracion','choice',array(
												'required'=>false,
												'choices' => $this->estadosManager->getNotasPosibles(),
												'expanded' => false,
												'label' => 'Valoración',
												))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_presentacionexternatype';
    }
}
