<?php
namespace Rooty\DefaultBundle\Extension;

use Symfony\Component\Routing\Generator\UrlGenerator;

class NumeralExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'numeral'  => new \Twig_Filter_Method($this, 'numeralFilter'),
        );
    }

    public function numeralFilter($sentence, $word0, $word1, $word2) {
        $separator = ' ';
        
        if (preg_match('/1\d$/', $sentence)) {
            return $sentence.$separator.$word2;
        } elseif (preg_match('/1$/', $sentence)) {
            return $sentence.$separator.$word0;
        } elseif (preg_match('/(2|3|4)$/', $sentence)) {
            return $sentence.$separator.$word1;
        } else {
            return $sentence.$separator.$word2; 
        }
    }

    public function getName()
    {
        return 'numeral_extension';
    }
}
