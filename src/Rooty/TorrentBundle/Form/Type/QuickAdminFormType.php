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
            ->add('check_status', 'choice', array (
                'help_block' => 'Ололо-ололо',
                'choices' => array( 'unchecked' => 'Непроверенный', 
                                    'approved' => 'Проверенный', 
                                    'rejected' => 'В доработке', 
                                    'blocked' => 'Запрещён', 
                            ),
                'empty_value' => false,
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
                'empty_value' => false,
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