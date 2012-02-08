<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Torrent;

class QuickAdminFormType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('is_checked', null, array (
                'label' => 'Проверен:',
                'help_block' => 'Ололо-ололо',
                'required' => false,
            ))
            ->add('is_visible', null, array(
                'label' => 'Видимый:',
                'help_block' => 'Видимые торренты отображаются в списке раздач',
                'required' => false,
            ))
            ->add('is_blocked', null, array(
                'label' => 'Заблокирован:',
                'help_block' => 'Заблокированные раздачи не отображаются в списке и не обрабатываются анонсером',
                'required' => false,
            ))
            ->add('is_sticky', null, array(
                'label' => 'Прикреплён:',
                'help_block' => 'Прикреплённые раздачи всегда находятся наверху списка',
                'required' => false,
            ))
            ->add('discount', 'choice', array(
                'label' => 'Скидка:',
                'choices' => array('0' => '0%',  '10' => '10%', '20' => '20%', '30' => '30%', '40' => '40%', '50' => '50%', '60' => '60%', '70' => '70%', '80' => '80%', '90' => '90%', '100' => '100%'),
                'required' => false,
            ));
    }

    public function getName()
    {
        return 'rooty_torrentbundle_quickadminformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Rooty\TorrentBundle\Entity\Torrent',
        );
    }
}