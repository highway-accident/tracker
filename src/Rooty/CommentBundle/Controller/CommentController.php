<?php

namespace Rooty\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Rooty\CommentBundle\Entity\Comment;
use Rooty\CommentBundle\Form\Type\CommentFormType;

/**
* Comments controller
*
* @Route("/comments")
*/
class CommentController extends Controller
{
    /**
    * Lists all Comment entities
    * @Template()
    * @Secure(roles="ROLE_USER")
    */
    public function indexAction($torrent_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyCommentBundle:Comment')->findByTorrent($torrent_id);
        
        return array('entity' => $entity);
    }
    
    /**
    * Displays a form to create a new Comment entity.
    * @Template()
    * @Secure(roles="ROLE_USER")
    */
    public function newAction($torrent_id)
    {
        $entity = new Comment();
        $form = $this->createForm(new CommentFormType(), $entity);
        
        return array(
            'torrent_id' => $torrent_id,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
     * Creates a new Comment entity. 
     * 
     * @Route("/{torrent_id}/create", name="comment_create")
     * @Method("post")
     * @Secure(roles="ROLE_USER")
     */
    public function createAction($torrent_id) {
        $entity = new Comment();
        $form = $this->createForm(new CommentFormType(), $entity);
        $request = $this->getRequest();
        
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $torrent = $em->getRepository('RootyTorrentBundle:Torrent')->findOneById($torrent_id);
            
            $entity->setTorrent($torrent);
            $entity->setAddedBy($user);
            $entity->setDateAdded(new \DateTime('now'));
            
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('torrent_show', array('id' => $torrent_id)));
        }
        return new Response('An error occured with form sumbission');
    }
    
    /**
    * Displays a form to edit an existing Comment entity.
    * @Route("/{id}/edit", name="comment_edit")
    * @Template()
    * @Secure(roles="ROLE_USER")
    */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyCommentBundle:Comment')->findOneById($id);
        
        $form = $this->createForm(new CommentFormType(), $entity);
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
    * Edit an existing Comment entity.
    * @Route("/{id}/update", name="comment_update")
    * @Template("RootyCommentBundle:Comment:edit.html.twig")
    * @Secure(roles="ROLE_USER")
    */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyCommentBundle:Comment')->findOneById($id);
        
        $form = $this->createForm(new CommentFormType(), $entity);
        
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $em->flush();
            $this->get('session')->setFlash('notice', 'Комментарий успешно отредактирован!');
            return $this->redirect($this->generateUrl('torrent_show', array('id' => $entity->getTorrent()->getId())));
        }
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
    * Delete an existing Comment entity.
    * @Route("/{id}/delete", name="comment_delete")
    * @Secure(roles="ROLE_USER")
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyCommentBundle:Comment')->findOneById($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity');
        }
        
        $em->remove($entity);
        $em->flush();
        
        $this->get('session')->setFlash('notice', 'Комментарий успешно удалён!');
        
        return $this->redirect($this->generateUrl('torrent_show', array('id' => $entity->getTorrent()->getId())));
    }
}
