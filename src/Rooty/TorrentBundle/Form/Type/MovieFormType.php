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
            ->add('type', 'hidden', array('data' => 'movies', 'property_path' => false))
                
            ->add('genre', 'text', array('label' => 'Жанр:'))
            ->add('director', 'text', array('label' => 'Режиссер:'))
            ->add('cast', 'text', array('label' => 'В ролях:'))
            ->add('country', 'text', array('label' => 'Страна:'))
            ->add('studio', 'text', array('label' => 'Кинокомпания:'))
            ->add('length', 'text', array('label' => 'Продолжительность:'))
            ->add('imdb_id', 'text', array('label' => 'ID фильма на IMDB:'))
            ->add('kinopoisk_id', 'text', array('label' => 'ID фильма на Кинопоиск.ру:'))
            ->add('translation', 'entity', array(
                'label' => 'Перевод:',
                'class' => 'RootyTorrentBundle:MovieTranslation',
                'property' => 'title',
            ))
            ->add('subtitles', 'text', array('label' => 'Субтитры:'))
            ->add('format', 'entity', array(
                'label' => 'Формат:',
                'class' => 'RootyTorrentBundle:MovieFormat',
                'property' => 'title',
            ))
            ->add('quality', 'entity', array(
                'label' => 'Качество:',
                'class' => 'RootyTorrentBundle:MovieQuality',
                'property' => 'title',
            ))
            ->add('video', null, array('label' => 'Видео:'))
            ->add('audio', null, array('label' => 'Аудио:'))
            ->add('sample_file', null, array('label' => 'Семпл:'))
            ->add('release_group', 'entity', array(
                'label' => 'Релиз-группа:',
                'class' => 'RootyTorrentBundle:MovieReleaseGroup',
                'property' => 'title',
            ));
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