<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Type;

class TypeFormType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
//            ->add('type', 'entity', array(
//                'label' => 'Тип:',
//                'class' => 'RootyTorrentBundle:Type',
//                'property' => 'title',
//            ));
            ->add('type', 'choice', array(
                'label' => 'Тип:',
                'choices' => array(
                    'games' => 'Игры',
                    'movies' => 'Фильмы',
                )
            ));
    }

    public function getName()
    {
        return 'rooty_torrentbundle_typeformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Rooty\TorrentBundle\Entity\Type',
        );
    }
}