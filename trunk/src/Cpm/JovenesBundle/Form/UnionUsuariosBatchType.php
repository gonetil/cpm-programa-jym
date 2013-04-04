<?php
/*
 * Created on 03/04/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
 
namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UnionUsuariosBatchType extends UsuarioBatchType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	
    	$unionForm = $options['data'];
    	
        $builder->add('usuarioFinal','hidden',array())
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_unionusuariosbatchtype';
    }
}