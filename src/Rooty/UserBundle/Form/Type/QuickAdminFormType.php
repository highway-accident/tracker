<?php

namespace Rooty\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\UserBundle\Entity\User;

class QuickAdminFormType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', array (
                'choices' => array( 'ROLE_USER' => 'Пользователь',
                                    'ROLE_SUPERUSER' => 'Суперпользователь',
                                    'ROLE_UPLOADER' => 'Аплоадер',
                                    'ROLE_SUPERUPLOADER' => 'Супераплоадер',
                                    'ROLE_MODERATOR_GAMES' => 'Модератор раздела игр',
                                    'ROLE_MODERATOR_MOVIES' => 'Модератор раздела фильмов',
                                    'ROLE_SUPER_MODERATOR' => 'Супермодератор',
                                    'ROLE_ADMIN' => 'Администратор',
                                    'ROLE_SUPER_ADMIN' => 'СисОп',
                            ),
                'label' => 'Роли пользователя:',
                'multiple' => true,
                'expanded' => true,
                'empty_value' => false,
                'required' => false,
                'help_block' => 'Роли наследуются по возрастанию',
            ))
            ->add('is_gold', 'choice', array (
                'choices' => array('1' => 'Да',  '0' => 'Нет'),
                'label' => 'Золотой:',
                'empty_value' => false,
                'required' => false,
                'help_block' => 'Даунлоад золотого пользователя не учитывается',
            ))
            ->add('locked', 'choice', array (
                'choices' => array('1' => 'Да',  '0' => 'Нет'),
                'label' => 'Заблокирован:',
                'empty_value' => false,
                'required' => false,
            ));
    }

    public function getName()
    {
        return 'rooty_userbundle_quickadminformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Rooty\UserBundle\Entity\User',
        );
    }
}