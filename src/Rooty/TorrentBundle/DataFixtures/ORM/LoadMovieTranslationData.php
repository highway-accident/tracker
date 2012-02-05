<?php
namespace Rooty\TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Rooty\TorrentBundle\Entity\MovieTranslation;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieTranslationData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entity = new MovieTranslation();
        $entity->setTitle('Оригинал');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Любительский (один голос)');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Любительский (многоголосный)');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Профессиональный (многоголосный)');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Гоблин');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Дублированный');
        $manager->persist($entity);
        
        $entity = new MovieTranslation();
        $entity->setTitle('Субтитры');
        $manager->persist($entity);
        
        $manager->flush();
    }
}
