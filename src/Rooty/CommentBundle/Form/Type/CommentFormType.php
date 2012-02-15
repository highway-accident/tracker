<?php

namespace Rooty\CommentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentFormType extends AbstractType
{ 
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('text', null, array(
                'label' => 'Текст комментария:'
            ));
    }

    public function getName()
    {
        return 'rooty_commentbundle_commentformtype';
    }
}