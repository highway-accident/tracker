<?php

namespace Rooty\TorrentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class TorrentRepository extends EntityRepository
{
    public function getListQuery(array $filterData = null)
    {
        $em = $this->getEntityManager();
        
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('RootyTorrentBundle:Torrent', 't');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'title', 'title');
        $rsm->addFieldResult('t', 'title_original', 'title_original');
        $rsm->addFieldResult('t', 'size', 'size');
        $rsm->addMetaResult('t', 'added_by_id', 'added_by_id');
        
        $sql = "SELECT t.id, t.title, t.title_original, t.size, t.added_by_id FROM torrents AS t";
        $parameters = array();
        
        foreach ($filterData as $key => $value) {
            if (empty($value)) {
                continue;
            }
            
            switch ($key) {
                case 'type':
                    echo 'type..';
                    switch ($value->getSlug()) {
                        case 'games':
                            $sql .= ", games AS g WHERE t.id = g.torrent_id";
                            break;
                        case 'movies':
                            $sql .= ", movies AS m WHERE t.id = m.torrent_id";
                            break;
                    }
                    break;
                case 'title':
                    echo 'title..';
                    //$qb
                        //->andWhere($qb->expr()->like('t.title', ':title'))
                        //->setParameter('title', '%'.$value.'%')
                        $sql .= " AND t.title LIKE :title";
                        $parameters['title'] = '%'.$value.'%';
                    //;
                    break;
                case 'size_min':
                    echo 'size_min..';
                    //$qb
                        //->andWhere('t.size > :size_min')
                        //->setParameter('size_min', $value)
                        $sql .= " AND t.size > :size_min";
                        $parameters['size_min'] = $value;
                    //;
                    break;
                case 'size_max':
                    echo 'size_max..';
                    //$qb
                        //->andWhere('t.size < :size_max')
                        //->setParameter('size_max', $value)
                        $sql .= " AND t.size < :size_max";
                        $parameters['size_max'] = $value;
                    //;
                    break;
                
                //movie fields
                case 'director':
                    echo 'director..';
                    //$qb
                        //->andWhere($qb->expr()->like('m.director', ':director'))
                        //->setParameter('director', '%'.$value.'%')
                        $sql .= " AND m.director LIKE :director";
                        $parameters['director'] = '%'.$value.'%';
                    //;
                    break;
                default:
            }
        }
        
        $query = $em->createNativeQuery($sql, $rsm);
        foreach ($parameters as $key => $value) {
            $query->setParameter($key, $value);
        }
        
        return $query;
        //return $qb->getQuery();
    }
}