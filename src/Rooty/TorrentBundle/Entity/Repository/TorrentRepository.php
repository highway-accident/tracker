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
        /*$rsm->addEntityResult('RootyTorrentBundle:Torrent', 't');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'title', 'title');
        $rsm->addFieldResult('t', 'title_original', 'title_original');
        $rsm->addFieldResult('t', 'size', 'size');
        $rsm->addMetaResult('t', 'added_by_id', 'added_by_id');*/
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('title_original', 'title_original');
        $rsm->addScalarResult('size', 'size');
        $rsm->addScalarResult('author_id', 'author_id');
        $rsm->addScalarResult('author_username', 'author_username');
        $rsm->addScalarResult('seeders', 'seeders');
        $rsm->addScalarResult('leechers', 'leechers');
        
        $sql = "SELECT t.id, t.title, t.title_original, t.size, u.id AS author_id, u.username AS author_username, 0 AS seeders, 0 AS leechers 
                FROM torrents AS t";
        $parameters = array();
        $whereClauses = array();
        
        var_dump($filterData);
        
        foreach ($filterData as $key => $value) {
            if (empty($value)) {
                continue;
            }
            
            switch ($key) {
                case 'type':
                    //echo 'type..';
                    switch ($value->getSlug()) {
                        case 'games':
                            $sql .= " JOIN games AS g ON g.torrent_id = t.id";
                            break;
                        case 'movies':
                            $sql .= " JOIN movies AS m ON m.torrent_id = t.id";
                            break;
                    }
                    break;
                case 'title':
                    //echo 'title..';
                    $whereClauses[] = "t.title LIKE :title";
                    $parameters['title'] = '%'.$value.'%';
                    break;
                case 'size_min':
                    //echo 'size_min..';
                    $whereClauses[] = "t.size > :size_min";
                    $parameters['size_min'] = intval($value);
                    break;
                case 'size_max':
                    //echo 'size_max..';
                    $whereClauses[] = "t.size < :size_max";
                    $parameters['size_max'] = intval($value);
                    break;
                
                //movie fields
                case 'director':
                    //echo 'director..';
                    $whereClauses[] = "m.director LIKE :director";
                    $parameters['director'] = '%'.$value.'%';
                    break;
                default:
                    break;
            }
        }
        
        $sql .= " JOIN users AS u ON u.id = t.added_by_id";
        
        // Add where clauses
        if (count($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        
        // Ordering
        $sql .= " ORDER BY t.is_sticky DESC";
        
        if (isset($filterData['order_by'])) {
            $validColumns = '/t.title|t.size|t.seeders|t.leechers/';
            $validDirections = '/ASC|DESC/';

            if (
            preg_match($validColumns, $filterData['order_by']) && 
            preg_match($validDirections, $filterData['order_direction'])) {
                $sql .= ", ".$filterData['order_by']." ".$filterData['order_direction'];
            }
            echo $sql;
        }
        
        $query = $em->createNativeQuery($sql, $rsm);
        foreach ($parameters as $key => $value) {
            $query->setParameter($key, $value);
        }
        
        return $query;
    }
}