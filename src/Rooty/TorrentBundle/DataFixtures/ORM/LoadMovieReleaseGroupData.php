<?php
namespace Rooty\TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Rooty\TorrentBundle\Entity\MovieReleaseGroup;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieReleaseGroupData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entity = new MovieReleaseGroup();
        $entity->setTitle('RelizLab');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('NovaFilm');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('Кубик в Кубе');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('РиперАМ');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('LostFilm');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('HD Tracker');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('HDClub');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('Электричка');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('Kuraj-Bambey');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('BigFANgroup');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('HQViDEO');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('Bluebird');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('Uniongang');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('VANO');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $entity = new MovieReleaseGroup();
        $entity->setTitle('HDTVshek');
        $entity->setImageUrl('not_yet');
        $manager->persist($entity);
        
        $manager->flush();
    }
}
