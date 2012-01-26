<?php

namespace Rooty\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username', null, array (
                'error_bubbling' => true,
            ))
            ->add('email', 'text', array (
                'error_bubbling' => true,
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Введёные вами пароли не совпадают',
                'error_bubbling' => true,
            ));
    }

    public function getName()
    {
        return 'rooty_user_registration';
    }
}
