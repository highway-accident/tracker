<?php
namespace Rooty\TorrentBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MovieFilterType extends AbstractType {
    public function  buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('director', 'text', array (
                'attr' => array (
                    'class' => 'input-xlarge',
                ),
                'required' => false,
            ))
            ->add('min_quality', 'text', array (
                'attr' => array (
                    'class' => 'input-xlarge',
                ),
                'required' => false,
            ));
    }
    
    public function getName() 
    {
        return 'rooty_torrentbundle_moviefiltertype';
    }
}
?>
