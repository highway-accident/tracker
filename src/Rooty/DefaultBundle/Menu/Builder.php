<?php
namespace Rooty\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {
    public function mainMenuAnonymous(FactoryInterface $factory) {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav');
        
        $menu->addChild('Главная', array('route' => '_index'));
        $menu->addChild('Войти', array('route' => 'fos_user_security_login'));
        $menu->addChild('Регистрация', array('route' => 'fos_user_registration_register'));
        
        return $menu;
    }
    
    public function mainMenuUser(FactoryInterface $factory) {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav');
        
        $menu->addChild('Главная', array('route' => '_index'));
        $menu->addChild('Профиль', array('route' => 'fos_user_profile_show'));
        $menu->addChild('Скачать', array('route' => 'torrents'));
        $menu->addChild('Загрузить', array('route' => 'torrent_new'));
        $menu->addChild('Выйти', array('route' => 'fos_user_security_logout'));
        
        return $menu;
    }
}
?>
