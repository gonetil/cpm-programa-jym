<?php
/*
 * Created on 28/04/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class VoluntarioSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('voluntario', 'jquery_entity_autocomplete', array(
																	'required'=>true, 																	 
																	'label'=> 'Voluntario',
																	'class' => 'CpmJovenesBundle:Voluntario',
																	'property' => 'id',
																	'url' => 'voluntario_online_search',
															))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_voluntariotype';
    }
}
