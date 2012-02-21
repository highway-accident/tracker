<?php
namespace Rooty\DefaultBundle\Extension;

use Symfony\Component\Routing\Generator\UrlGenerator;

class timeElapsedExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'timeElapsed'  => new \Twig_Filter_Method($this, 'timeElapsedFilter'),
        );
    }

    public function timeElapsedFilter($sentence, $from) {
        if ($from == 'now') {
            $from = new \DateTime;
        }
        
        return $from->diff($sentence);
    }

    public function getName()
    {
        return 'time_elapsed_extension';
    }
}
