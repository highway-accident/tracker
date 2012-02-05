<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Torrent;

class TorrentFormType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('title_original')
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
    
    private function addMovieFields(FormBuilder $builder)
    {
        $builder
            ->add('original_name', 'text', array('label' => 'Оригинальное название:', 'data' => (isset($this->data['original_name'])) ? $this->data['original_name'] : null, 'property_path' => false))
            ->add('genre', 'text', array('label' => 'Жанр:', 'data' => (isset($this->data['genre'])) ? $this->data['genre'] : null, 'property_path' => false))
            ->add('director', 'text', array('label' => 'Режиссер:', 'data' => (isset($this->data['director'])) ? $this->data['director'] : null, 'property_path' => false))
            ->add('cast', 'text', array('label' => 'В ролях:', 'data' => (isset($this->data['cast'])) ? $this->data['cast'] : null, 'property_path' => false))
            ->add('country', 'text', array('label' => 'Страна и\или кинокомпания:', 'data' => (isset($this->data['country'])) ? $this->data['country'] : null, 'property_path' => false))
            ->add('run_time', 'text', array('label' => 'Продолжительность:', 'data' => (isset($this->data['run_time'])) ? $this->data['run_time'] : null, 'property_path' => false))
            ->add('id_kp', 'text', array('label' => 'ID фильма на Кинопоиск.ру:', 'data' => (isset($this->data['id_kp'])) ? $this->data['id_kp'] : null, 'property_path' => false))
            ->add('description', null, array('label' => 'Описание:'))
            ->add('translation', 'text', array('label' => 'Перевод:', 'data' => (isset($this->data['translation'])) ? $this->data['translation'] : null, 'property_path' => false))
            ->add('subtitle', 'text', array('label' => 'Субтитры:', 'data' => (isset($this->data['subtitle'])) ? $this->data['subtitle'] : null, 'property_path' => false))
            ->add('format', 'text', array('label' => 'Формат:', 'data' => (isset($this->data['format'])) ? $this->data['format'] : null, 'property_path' => false))
            ->add('quality', 'text', array('label' => 'Качество:', 'data' => (isset($this->data['quality'])) ? $this->data['quality'] : null, 'property_path' => false))
            ->add('video', 'text', array('label' => 'Видео:', 'data' => (isset($this->data['video'])) ? $this->data['video'] : null, 'property_path' => false))
            ->add('audio', 'text', array('label' => 'Аудио:', 'data' => (isset($this->data['audio'])) ? $this->data['audio'] : null, 'property_path' => false))
            ->add('sample', 'text', array('label' => 'Семпл:', 'data' => (isset($this->data['sample'])) ? $this->data['sample'] : null, 'property_path' => false))
            ->add('release', 'text', array('label' => 'Релиз:', 'data' => (isset($this->data['release'])) ? $this->data['release'] : null, 'property_path' => false));
    }

    public function getName()
    {
        return 'rooty_torrentbundle_torrentformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Rooty\TorrentBundle\Entity\Torrent',
        );
    }
}