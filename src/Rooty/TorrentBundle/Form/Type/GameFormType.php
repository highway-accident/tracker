<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Torrent;

class MovieFormType extends AbstractType
{
    private $mode; //are we creating a new entity or editing existing
    
    public function __construct($mode) {
        $this->mode = $mode;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('torrent', new TorrentFormType())
            ->add('type', 'hidden', array('data' => 'games', 'property_path' => false))
                
            ->add('genre', 'text', array('label' => 'Жанр:'))
            ->add('developer', 'text', array('label' => 'Разработчик:'))
            ->add('publisher', 'text', array('label' => 'Издатель:'))
            ->add('system_requirements', 'textarea', array('label' => 'Системные требования:'))
            ->add('crack_url', 'text', array('label' => 'Ссылка на кряк:'))
            ->add('how_to_run', 'textarea', array('label' => 'Запуск:'));
    }
    
    
    public function getName()
    {
        return 'rooty_torrentbundle_typeformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        if($this->mode == 'new') {
            return array('validation_groups' => array('new'));
        } else {
            return array('validation_groups' => array('edit'));
        }
    }
}