<?php

namespace Rooty\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', 'text')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
            ));
    }

    public function getName()
    {
        return 'rooty_user_registration';
    }
}
