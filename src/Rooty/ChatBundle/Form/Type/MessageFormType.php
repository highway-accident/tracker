<?php

namespace Rooty\ChatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('text', 'text');
    }

    public function getName()
    {
        return 'rooty_chatbundle_messageformtype';
    }
}
