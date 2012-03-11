<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Torrent;

class TorrentFormType extends AbstractType
{
    private $mode; //are we creating a new entity or editing existing
    
    public function __construct($mode) {
        $this->mode = $mode;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('title_original')
            ->add('year')
            ->add('description', null, array('label' => 'Описание:'))
            ->add('torrent_file', null, array('required' => false))
            ->add('poster_file', null, array('required' => false))
            ->add('screenshots', 'collection', array(
                'type' => new ScreenshotFormType(),
                'label' => 'Скриншоты:',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ));
    }

    public function getName()
    {
        return 'rooty_torrentbundle_torrentformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        if($this->mode == 'new') {
            return array(
                //'validation_groups' => array('new'),
                'data_class' => 'Rooty\TorrentBundle\Entity\Torrent',
            );
        } else {
            return array(
                //'validation_groups' => array('edit'),
                'data_class' => 'Rooty\TorrentBundle\Entity\Torrent',
            );
        }
    }
}