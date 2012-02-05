<?php
namespace Rooty\TorrentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

class TorrentStatus implements ChoiceListInterface
{
    const TYPE_ALL = 'Все';
    const TYPE_ALIVE = 'Только живые';
    const TYPE_DEAD = 'Только мёртвые';
    
    public function getChoices()
    {
        return array(
            self::TYPE_ALIVE => self::TYPE_ALIVE,
            self::TYPE_ALL => self::TYPE_ALL,
            self::TYPE_DEAD => self::TYPE_DEAD,
        );
    }
}