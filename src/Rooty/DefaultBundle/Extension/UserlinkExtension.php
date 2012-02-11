<?php
namespace Rooty\DefaultBundle\Extension;

use Symfony\Component\Routing\Generator\UrlGenerator;

class UserlinkExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'userlink'  => new \Twig_Filter_Method($this, 'userlinkFilter'),
        );
    }

    public function userlinkFilter($sentence, $path, $roles) {
        $class = 'user';
        
        if (in_array('ROLE_ADMIN', $roles)) {
            $class = 'admin';
        } elseif (in_array('ROLE_SUPER_MODERATOR', $roles)) {
            $class = 'super_moderator';
        } elseif (in_array('ROLE_MODERATOR', $roles)) {
            $class = 'moderator';
        } elseif (in_array('ROLE_GOLD', $roles)) {
            $class = 'gold';
        } elseif (in_array('ROLE_SUPER_UPLOADER', $roles)) {
            $class = 'super_uploader';
        } elseif (in_array('ROLE_UPLOADER', $roles)) {
            $class = 'uploader';
        } elseif (in_array('ROLE_SUPERUSER', $roles)) {
            $class = 'super_user';
        }
        return '<a href="'.$path.'" class="'.$class.'">'.$sentence.'</a>';
    }

    public function getName()
    {
        return 'userlink_extension';
    }
}
