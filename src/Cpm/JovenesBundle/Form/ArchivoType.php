<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ArchivoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre','file',array('label' => 'Archivo', 'required'=>false))
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_archivotype';
    }
}
