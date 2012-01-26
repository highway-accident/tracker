<?php

namespace Rooty\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ResettingFormType as BaseType;

class ResettingFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('new', 'repeated', array (
            'type' => 'password',
            'invalid_message' => 'Введёные вами пароли не совпадают',
            'error_bubbling' => true,
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'FOS\UserBundle\Form\Model\ResetPassword');
    }

    public function getName()
    {
        return 'rooty_user_resetting';
    }
}
