<?php

namespace Rooty\TorrentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TorrentRepository extends EntityRepository
{
    public function getListQuery(array $filterData = null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        foreach ($filterData as $key => $value) {
            switch ($key) {
                case 'type':
                    //echo 'type..';
                    switch ($value->getSlug()) {
                        case 'games':
                            $qb
                                ->select('g') 
                                ->from('RootyTorrentBundle:Game', 'g')
                                ->join('g.torrent', 't')
                            ;
                            break;
                    }
                    break;
                case 'title':
                    $qb
                        ->andWhere($qb->expr()->like('t.title', ':title'))
                        ->setParameter('title', '%'.$value.'%')
                    ;
                    break;
                case 'size_min':
                    //echo 'size_min..';
                    $qb
                        ->andWhere('t.size > :size_min')
                        ->setParameter('size_min', $value)
                    ;
                    break;
                case 'size_max':
                    //echo 'size_max..';
                    $qb
                        ->andWhere('t.size < :size_max')
                        ->setParameter('size_max', $value)
                    ;
                    break;
                
                //movie fields
                case 'director':
                    //echo 'director..';
                    $qb
                        ->andWhere('t.size < :size_max')
                        ->setParameter('size_max', $value)
                    ;
                    break;
            }
        }
        
        return $qb->getQuery();
    }
}