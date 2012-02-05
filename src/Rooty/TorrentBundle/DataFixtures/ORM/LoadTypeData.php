<?php
namespace Rooty\TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Rooty\TorrentBundle\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entity = new Type();
        $entity->setTitle('Игры');
        $entity->setSlug('games');
        $entity->setImageUrl('not yet');
        $manager->persist($entity);
        
        $entity = new Type();
        $entity->setTitle('Фильмы');
        $entity->setSlug('movies');
        $entity->setImageUrl('not yet');
        $manager->persist($entity);
        
        $manager->flush();
    }
}
