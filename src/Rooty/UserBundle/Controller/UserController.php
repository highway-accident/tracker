<?php

namespace Rooty\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
* Users controller
*
* @Route("/users")
*/
class UserController extends Controller
{
    /**
    * Lists all User entities
    * @Route("/", name="users") 
    * @Template()
    * @Secure(roles="ROLE_USER")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyUserBundle:User')->findAll();
        
        return array(
            'entity' => $entity,
        );
    }
    
    /**
    * Finds and displays a User entity
    * @Route("/{id}/show", name="user_show") 
    * @Template()
    * @Secure(roles="ROLE_USER")
    */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('RootyUserBundle:User')->findOneById($id);
        $torrentsUploaded = $em->getRepository('RootyTorrentBundle:Torrent')->findBy(array('added_by' => $id));
        $torrentsDownloaded = $em->getRepository('RootyTorrentBundle:TorrentUserStats')->findBy(array('user' => $id, 'finished' => 'yes'));
        
        $query = $em->createQuery('SELECT COUNT(c.id) FROM Rooty\CommentBundle\Entity\Comment c');
        $commentCount = $query->getSingleScalarResult();
        
        return array(
            'user' => $user,
            'comment_count' => $commentCount,
            'torrents_uploaded' => $torrentsUploaded,
            'torrents_downloaded' => $torrentsDownloaded,
        );
    }
}
