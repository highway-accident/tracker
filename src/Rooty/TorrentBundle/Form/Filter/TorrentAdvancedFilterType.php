<?php

namespace Rooty\TorrentBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TorrentBundle\Form\Extension\ChoiceList\TorrentStatus as Status;
use Rooty\TorrentBundle\Form\Extension\ChoiceList\TorrentType as Type;

class TorrentAdvancedFilterType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title', 'text', array (
                'attr' => array(
                    'class' => 'input-xlarge',
                    'placeholder' => 'Название',
                ),
                'required' => false,
            ))
            ->add('status', 'choice', array (
                'attr' => array(
                    'class' => 'input-xlarge'
                ),
                'choice_list' => new Status(),
                'empty_value' => false,
                'required' => false,
            ))
            ->add('type', 'entity', array (
                'attr' => array(
                    'class' => 'input-xlarge'
                ),
                'class' => 'RootyTorrentBundle:Type',
                'property' => 'title',
                'empty_value' => false,
                'expanded' => false,
                'multiple' => false,
                'required' => false,
            ))
            ->add('size_min', 'text', array (
                'data' => '0',
                'required' => false,
            ))
            ->add('size_max', 'text', array (
                'data' => '322122547200',
                'required' => false,
            ))
            ->add('movie', 'collection', array(
                'type' => new MovieFilterType(),
                'prototype' => true,
                'by_reference' => false,
            ))
                
            ->add('director', 'text', array (
                'attr' => array (
                    'class' => 'input-xlarge',
                ),
                'required' => false,
                'property_path' => false,
            ))
            ->add('min_quality', 'text', array (
                'attr' => array (
                    'class' => 'input-xlarge',
                ),
                'required' => false,
                'property_path' => false,
            ));
    }
    
    public function getName()
    {
        return 'rooty_torrentbundle_torrentadvancedfiltertype';
    }
}
