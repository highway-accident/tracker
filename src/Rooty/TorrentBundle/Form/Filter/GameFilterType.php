<?php
namespace Rooty\TorrentBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GameFilterType extends AbstractType {
    public function  buildForm(FormBuilder $builder, array $options)
    {
        
    }
    
    public function getName() 
    {
        return 'rooty_torrentbundle_gamefiltertype';
    }
}
?>
