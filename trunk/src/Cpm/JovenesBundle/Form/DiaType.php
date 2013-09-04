<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DiaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('tanda')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_diatype';
    }
}
