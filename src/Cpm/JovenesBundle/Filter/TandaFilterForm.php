<?php

namespace Cpm\JovenesBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class TandaFilterForm extends ModelFilterForm
{
    public function buildForm(FormBuilder $builder, array $options)
    {
     		$miCiclo = new CicloFilter();
       		$builder->add('cicloFilter',$miCiclo->createForm($this->getJYM()),array('label'=>'Ciclo'));
		
    }
}