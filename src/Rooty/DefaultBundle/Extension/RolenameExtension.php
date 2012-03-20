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
        $role = 'Пользователь';
        $class = 'user';
        $context = $this->container->get('security.context');
        
        if ($context->isGranted('ROLE_SUPER_ADMIN')) {
            $role = 'СисОп';
            $class = 'admin';
        } elseif ($context->isGranted('ROLE_ADMIN')) {
            $role = 'Администратор';
            $class = 'admin';
        } elseif ($context->isGranted('ROLE_SUPER_MODERATOR')) {
            $role = 'Супермодератор';
            $class = 'super_moderator';
        } elseif ($context->isGranted('ROLE_MODERATOR')) {
            $names = array();
            $categories = array('GAMES' => 'Игры', 'MOVIES' => 'Кино');
            
            foreach ($categories as $slug => $name) {
                if ($user->hasRole('ROLE_MODERATOR_'.$slug)) {
                    $names[] = $name;
                }
            }
            $role = 'Модератор разделов: ' . implode($names, ', ');
            $class = 'moderator';
        } elseif ($context->isGranted('ROLE_SUPER_UPLOADER')) {
            $role = 'Супераплоадер';
            $class = 'super_uploader';
        } elseif ($context->isGranted('ROLE_UPLOADER')) {
            $role = 'Аплоадер';
            $class = 'uploader';
        } elseif ($context->isGranted('ROLE_SUPERUSER')) {
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
