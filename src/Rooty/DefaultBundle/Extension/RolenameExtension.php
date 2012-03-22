<?php
namespace Rooty\DefaultBundle\Extension;

class RolenameExtension extends \Twig_Extension {
    protected $containter;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public function getContainer() {
        return $this->container;     
    }
    
    public function getFilters() {
        return array(
            'rolename'  => new \Twig_Filter_Method($this, 'rolenameFilter'),
        );
    }

    public function rolenameFilter($user) {
        $roles = $user->getRoles();
        $role = 'Пользователь';
        $class = 'user';
        $context = $this->container->get('security.context');
        
        if (in_array('ROLE_SUPER_ADMIN', $roles)) {
            $role = 'СисОп';
            $class = 'admin';
        } elseif (in_array('ROLE_ADMIN', $roles)) {
            $role = 'Администратор';
            $class = 'admin';
        } elseif (in_array('ROLE_SUPER_MODERATOR', $roles)) {
            $role = 'Супермодератор';
            $class = 'super_moderator';
        } elseif (in_array('ROLE_MODERATOR', $roles)) {
            $names = array();
            $categories = array('GAMES' => 'Игры', 'MOVIES' => 'Кино');
            
            foreach ($categories as $slug => $name) {
                if ($user->hasRole('ROLE_MODERATOR_'.$slug)) {
                    $names[] = $name;
                }
            }
            $role = 'Модератор разделов: ' . implode($names, ', ');
            $class = 'moderator';
        } elseif (in_array('ROLE_SUPER_UPLOADER', $roles)) {
            $role = 'Супераплоадер';
            $class = 'super_uploader';
        } elseif (in_array('ROLE_UPLOADER', $roles)) {
            $role = 'Аплоадер';
            $class = 'uploader';
        } elseif (in_array('ROLE_SUPERUSER', $roles)) {
            $role = 'Суперпользователь';
            $class = 'super_user';
        }
         
        return '<span class="'.$class.'">'.$role.'</span>';
    }

    public function getName()
    {
        return 'rolename_extension';
    }
}
