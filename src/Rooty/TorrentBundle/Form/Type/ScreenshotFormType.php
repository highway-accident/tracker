<?php

namespace Rooty\TorrentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rooty\TrackerBundle\Entity\Screenshot;

class ScreenshotFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Название:'))
            ->add('screenshot_file', 'file', array('label' => 'Файл:', 'required' => false));
    }
    
    public function getName()
    {
        return 'rooty_torrentbundle_screenshotformtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Rooty\TorrentBundle\Entity\Screenshot',
        );
    }
}