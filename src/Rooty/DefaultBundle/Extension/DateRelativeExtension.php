<?php
namespace Rooty\DefaultBundle\Extension;

use Symfony\Component\Routing\Generator\UrlGenerator;

class DateRelativeExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'dateRelative'  => new \Twig_Filter_Method($this, 'dateRelativeFilter'),
        );
    }

    public function dateRelativeFilter($sentence, $format) {
        $event = $sentence->getTimestamp();
        
        if ($event > strtotime("today")) {
            return 'Сегодня в ' . $sentence->format('H:i');;
        } else if ($event > strtotime("yesterday")) {
            return 'Вчера в ' . $sentence->format('H:i');
        } else {
            return $sentence->format($format);
        }
    }

    public function getName()
    {
        return 'daterelative_extension';
    }
}