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
        $rsm->addScalarResult('seeders', 'seeders');
        $rsm->addScalarResult('leechers', 'leechers');
        $rsm->addScalarResult('type_slug', 'type_slug');
        $rsm->addScalarResult('type_name', 'type_name');
        $rsm->addScalarResult('type_image', 'type_image');
        
        $sql = "SELECT t.id, t.title, t.title_original, t.size, u.id AS author_id, ty.slug AS type_slug, ty.title AS type_name, ty.image_url AS type_image, IFNULL(seedcount, 0) AS seeders, IFNULL(leechcount, 0) AS leechers 
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
        
        $sql .= " JOIN torrent_types AS ty ON ty.id = t.type_id";
        
        $sql .= " LEFT JOIN
                (SELECT info_hash, COUNT(*) AS seedCount FROM peers 
                WHERE complete = 1
                GROUP BY info_hash) AS seeders
                ON seeders.info_hash = t.info_hash";
        
        $sql .= " LEFT JOIN
                (SELECT info_hash, COUNT(*) AS leechCount FROM peers 
                WHERE complete = 0
                GROUP BY info_hash) AS leechers
                ON leechers.info_hash = t.info_hash";
        
        // Add where clauses
        if (count($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        
        // Ordering
        $sql .= " ORDER BY t.is_sticky DESC";
        
        if (isset($filterData['order_by'])) {
            $validColumns = '/t.title|t.size|seeders|leechers/';
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
    
    public function getVotes($torrent_id)
    {
        $em = $this->getEntityManager();
        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('likes', 'likes');
        $rsm->addScalarResult('dislikes', 'dislikes');
        
        $sql =  "SELECT * FROM 
                (SELECT COUNT(*) AS likes FROM votes WHERE torrent_id = :torrent_id AND type='like') AS T1,
                (SELECT COUNT(*) AS dislikes FROM votes WHERE torrent_id = :torrent_id AND type='dislike') AS T2";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('torrent_id', $torrent_id);
        
        return $query->getSingleResult();
    }
    
    public function getUserVote($torrent_id, $user_id)
    {
        $em = $this->getEntityManager();
        
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('type', 'type');
        
        $sql =  "SELECT type FROM votes WHERE torrent_id = :torrent_id AND user_id = :user_id LIMIT 1";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('torrent_id', $torrent_id);
        $query->setParameter('user_id', $user_id);
        
        return $query->getOneOrNullResult();
    }
}