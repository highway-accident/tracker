<?php
namespace Rooty\TorrentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

class TorrentType implements ChoiceListInterface
{
    const TYPE_GAME = 'Игры';
    
    public function getChoices()
    {
        return array(
            self::TYPE_GAME => self::TYPE_GAME,
        );
    }
}