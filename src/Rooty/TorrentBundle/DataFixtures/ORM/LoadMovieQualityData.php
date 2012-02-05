<?php
namespace Rooty\TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Rooty\TorrentBundle\Entity\MovieQuality;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieQualityData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entity = new MovieQuality();
        $entity->setTitle('DVDRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('DVDScr');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('DVD-Video');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('TVRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('TS');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('TC');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('CamRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('TVRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('SATRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('HDTVRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('HDRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('HD-DVDRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('VHSRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('Blu-rayRip');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('HD DVD');
        $manager->persist($entity);
        
        $entity = new MovieQuality();
        $entity->setTitle('Blu-ray');
        $manager->persist($entity);
        
        $manager->flush();
    }
}
