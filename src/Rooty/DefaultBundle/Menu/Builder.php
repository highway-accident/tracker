<?php
namespace Rooty\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {
    public function mainMenu(FactoryInterface $factory) {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->setAttribute('class', 'header__menu clearfix');
        
        $menu->addChild('Главная', array('route' => '_index'));
        $menu->addChild('Скачать'/*, array('route' => 'torrents_download')*/);
        $menu->addChild('Загрузить'/*, array('route' => 'torrents_upload')*/);
        
        return $menu;
    }
}
?>
