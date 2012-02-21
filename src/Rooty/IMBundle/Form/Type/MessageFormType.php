<?php

namespace Rooty\IMBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('text', null, array(
                'label' => 'Текст сообщения:',
            ));
    }

    public function getName()
    {
        return 'rooty_messagebundle_messageformtype';
    }
}
