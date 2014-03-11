<?php
/*
 * Created on Mar 11, 2014
 * @author gonetil
 * project jym
 * Copyleft 2014
 * 
 */

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class VoluntarioFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    	
     $builder
            ->add('apellido',null,array('label'=>'Apellido', 'required'=>false))
            ->add('email',null,array('label'=>'Email', 'required'=>false))
        ;
    }
    
}
