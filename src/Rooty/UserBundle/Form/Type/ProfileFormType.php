<?php

namespace Rooty\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', null, array (
                'label' => 'Имя:',
            ))
            ->add('surname', null, array (
                'label' => 'Фамилия:',
            ))
            ->add('gender', 'choice', array(
                'label' => 'Пол:',
                'expanded' => true,
                'choices' => array('1' => 'Мужской', '2' => 'Женский'),
                'required' => false,
            ))
            ->add('birthday', 'birthday', array(
                'format' => 'dd&nbsp;MMMM&nbsp;yyyy',
                'label' => 'Дата рождения:',
                'required' => false,
            ))
            ->add('avatar_file', 'file', array (
                'label' => 'Аватар:',
                'help_block' => 'Максимальный размер изображения - 100 килобайт',
                'required' => false,
            ))
            ->add('icq')
            ->add('skype')
            ->add('jabber')
            ;
    }

    public function getName()
    {
        return 'rooty_user_profile';
    }
}
