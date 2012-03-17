<?php

namespace Cpm\JovenesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InvitacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('fechaCreacion')
            ->add('aceptoInvitacion')
            ->add('numeroAsistentes')
            ->add('solicitaViaje')
            ->add('solicitaHospedaje')
            ->add('observaciones')
            ->add('suplente')
            ->add('asistio')
            ->add('proyecto')
            ->add('instanciaEvento')
        ;
    }

    public function getName()
    {
        return 'cpm_jovenesbundle_invitaciontype';
    }
}
