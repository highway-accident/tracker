<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Torrent;

class TorrentFormType extends AbstractType
{
    private $type; //torrent type
    private $data; //custom field values
    
    public function __construct($type, $data) {
        $this->type = $type;
        $this->data = $data;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('title_original')
            ->add('torrent_file', null, array('required' => false))
            ->add('poster_file', null, array('required' => false))
            ->add('type', 'hidden', array('data' => $this->type, 'property_path' => false));
        
        switch ($this->type) {
            case 'game':
                $this->addGameFields($builder);
                break;
            default:
                throw new \Exception('Wrong torrent type!');
                break;
        }
        
        $builder
            ->add('screenshots', 'collection', array(
                'type' => new ScreenshotFormType(),
                'label' => 'Скриншоты:',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ));
    }
    
    private function addGameFields(FormBuilder $builder)
    {
        $builder
            ->add('genre', 'text', array('label' => 'Жанр:', 'data' => (isset($this->data['genre'])) ? $this->data['genre'] : null, 'property_path' => false))
            ->add('developer', 'text', array('label' => 'Разработчик:', 'data' => (isset($this->data['developer'])) ? $this->data['developer'] : null, 'property_path' => false))
            ->add('publisher', 'text', array('label' => 'Издатель:', 'data' => (isset($this->data['publisher'])) ? $this->data['publisher'] : null, 'property_path' => false))
            ->add('description', null, array('label' => 'Описание:'))
            ->add('system_requirements', 'textarea', array('label' => 'Системные требования:', 'data' => (isset($this->data['system_requirements'])) ? $this->data['system_requirements'] : null, 'property_path' => false))
            ->add('crack_url', 'text', array('label' => 'Ссылка на кряк:', 'data' => (isset($this->data['crack_url'])) ? $this->data['crack_url'] : null, 'property_path' => false))
            ->add('how_to_run', 'textarea', array('label' => 'Запуск:', 'data' => (isset($this->data['crack_url'])) ? $this->data['how_to_run'] : null, 'property_path' => false));
    }

    public function getName()
    {
        return 'rooty_torrentbundle_torrentformtype';
    }
}