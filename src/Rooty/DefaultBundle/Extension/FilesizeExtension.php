<?php
namespace Rooty\DefaultBundle\Extension;

class FilesizeExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'filesize'  => new \Twig_Filter_Method($this, 'filesizeFilter'),
        );
    }

    public function filesizeFilter($sentence) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $unitIndex = 0;
        while ($sentence > 1024 && $unitIndex < 4) {
            $sentence /= 1024;
            $unitIndex++;
        }
        return ($unitIndex > 2) ? 
            number_format($sentence, 2).' '.$units[$unitIndex] : 
            number_format($sentence, 0).' '.$units[$unitIndex];
    }

    public function getName()
    {
        return 'filesize_extension';
    }
}
