<?php
namespace Rooty\DefaultBundle\Extension;

class UserlinkExtension extends \Twig_Extension {
    protected $containter;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public function getContainer() {
        return $this->container;     
    }
    
    public function getFilters() {
        return array(
            'userlink'  => new \Twig_Filter_Method($this, 'userlinkFilter'),
        );
    }

    public function userlinkFilter($sentence) {
        $class = 'user';
        $username = $sentence->getUsername();
        $roles = $sentence->getRoles();
        $path = $this->container->get('router')->generate('user_show', array('id' => $sentence->getId()));
        
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
        return '<a href="'.$path.'" class="'.$class.'">'.$username.'</a>';
    }

    public function getName()
    {
        return 'userlink_extension';
    }
}
