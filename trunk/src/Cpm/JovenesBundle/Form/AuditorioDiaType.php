<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AuditorioDiaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('dia')
            ->add('auditorio')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_auditoriodiatype';
    }
}
