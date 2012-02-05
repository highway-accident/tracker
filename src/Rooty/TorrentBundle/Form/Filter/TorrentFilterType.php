<?php

namespace Rooty\TorrentBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TorrentBundle\Form\Extension\ChoiceList\TorrentStatus as Status;

class TorrentFilterType extends AbstractType
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
            ));
    }
    
    public function getName()
    {
        return 'rooty_torrentbundle_torrentfiltertype';
    }
}
