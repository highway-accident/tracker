<?php
namespace Rooty\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {
    public function mainMenuAnonymous(FactoryInterface $factory) {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->setAttribute('class', 'nav');
        
        $menu->addChild('Главная', array('route' => '_index'));
        $menu->addChild('Войти', array('route' => 'fos_user_security_login'));
        
        return $menu;
    }
    
    public function mainMenuUser(FactoryInterface $factory) {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->setAttribute('class', 'nav');
        
        $menu->addChild('Главная', array('route' => '_index'));
        $menu->addChild('Профиль', array('route' => 'fos_user_profile_show'));
        $menu->addChild('Скачать', array('route' => 'torrents'));
        $menu->addChild('Загрузить', array('route' => 'torrent_new', 'attributes' => array('class' => 'dropdown')));
        $menu['Загрузить']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['Загрузить']->setLinkAttribute('data-toggle', 'dropdown');
        $menu['Загрузить']->setChildrenAttributes(array('class' => 'dropdown-menu'));
        $menu['Загрузить']->addChild('1', array('uri' => '#'));
        $menu['Загрузить']->addChild('2', array('uri' => '#'));
        
        return $menu;
    }
}
?>
