<?php
namespace Rooty\TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Rooty\TorrentBundle\Entity\MovieFormat;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieFormatData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entity = new MovieFormat();
        $entity->setTitle('DivX');
        $manager->persist($entity);
        
        $entity = new MovieFormat();
        $entity->setTitle('XviD');
        $manager->persist($entity);
        
        $entity = new MovieFormat();
        $entity->setTitle('WMV');
        $manager->persist($entity);
        
        $entity = new MovieFormat();
        $entity->setTitle('AVI');
        $manager->persist($entity);
        
        $entity = new MovieFormat();
        $entity->setTitle('MKV');
        $manager->persist($entity);
        
        $entity = new MovieFormat();
        $entity->setTitle('VCD');
        $manager->persist($entity);
        
        $manager->flush();
    }
}
