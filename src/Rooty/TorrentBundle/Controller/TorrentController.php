<?php

namespace Rooty\TorrentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Rooty\TorrentBundle\Entity\Torrent;
use Rooty\TorrentBundle\Entity\Game;
use Rooty\TorrentBundle\Entity\Movie;
use Rooty\TorrentBundle\Form\Type\TypeFormType;
use Rooty\TorrentBundle\Form\Type\GameFormType;
use Rooty\TorrentBundle\Form\Type\MovieFormType;
use Rooty\TorrentBundle\Form\Filter\TorrentFilterType;
use Rooty\TorrentBundle\Form\Filter\TorrentAdvancedFilterType;
use Rooty\TorrentBundle\Form\Type\QuickAdminFormType;

/**
* Torrents controller
*
* @Route("/torrents")
*/
class TorrentController extends Controller
{
    /**
    * Lists all Torrent entities
    * @Route("/", name="torrents")
    * @Template()
    */
    public function indexAction()
    {
        $filterForm = $this->createForm(new TorrentFilterType());
        $advancedFilterForm = $this->createForm(new TorrentAdvancedFilterType());
        $em = $this->getDoctrine()->getEntityManager();
        $orderBy = 't.title';
        $orderDirection = 'ASC';
        
        $request = $this->getRequest();
        if ($request->query->has('search') || $request->query->has('search_advanced')) {
            if ($request->query->has('search')) {
                $currForm = $filterForm;
            } else if ($request->query->has('search_advanced')) {
                $currForm = $advancedFilterForm;
            }
            $currForm->bindRequest($request);
            $data = $currForm->getData();
            $query = $em->getRepository('RootyTorrentBundle:Torrent')->getListQuery($data);
            $orderBy = $data['order_by'];
            $orderDirection = $data['order_direction'];
        } else {
            $query = $em->getRepository('RootyTorrentBundle:Torrent')->getListQuery(array('order_by' => $orderBy, 'order_direction' => $orderDirection));
        }
        $entity = $query->getScalarResult();
        
        //var_dump($entity);
        
        return array(
            'entities' => $entity,
            'filter_form' => $filterForm->createView(),
            'advanced_filter_form' => $advancedFilterForm->createView(),
            'order_by' => $orderBy,
            'order_direction' => $orderDirection
        );
    }
    
    /**
     * Finds and displays a Torrent entity
     * 
     * @Route("/{id}/show", name="torrent_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $type = $this->getTorrentType($id);
        switch ($type) {
            case 'games':
                $entity = $em->getRepository('RootyTorrentBundle:Game')->findOneByTorrent($id);
                break;
            case 'movies':
                $entity = $em->getRepository('RootyTorrentBundle:Movie')->findOneByTorrent($id);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity.');
        }
        
        $quickAdminForm = $this->createForm(new QuickAdminFormType(), $entity->getTorrent());
        
        return array(
            'entity' => $entity,
            'admin_form' => $quickAdminForm->createView(),
        );
    }
    
    /**
     *
     * @Route("/{id}/adminUpdate", name="torrent_admin_update")
     */
    public function adminUpdateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyTorrentBundle:Torrent')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        
        $form = $this->createForm(new QuickAdminFormType(), $entity);
        $request = $this->getRequest();
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $entity->setCheckedBy($user);
            
            $em->flush();
            
            return $this->redirect($this->generateUrl('torrent_show', array('id' => $id)));
        }
    }
    
    
    /**
     * Sends torrent file with current user passkey
     * 
     * @Route("/{id}/download", name="torrent_download")
     * @Template()
     */
    public function downloadAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyTorrentBundle:Torrent')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        
        /* Set the announce url and user passkey */
        $announce_url = $this->container->getParameter('announce_url') . '?passkey=' . $user->getPasskey();
        $torrent = new \Torrent_Torrent($entity->getTorrentAbsolutePath());
        $torrent->announce($announce_url);
        $torrent->send();
    }

    /**
    * Displays a form to select a new Torrent entity type
    * @Route("/chooseType", name="torrent_choose_type")
    * @Template()
    */
    public function chooseTypeAction()
    {
        $form = $this->createForm(new TypeFormType());
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
    * Displays a form to create a new Torrent entity.
    * @Route("/new", name="torrent_new")
    * @Template()
    */
    public function newAction()
    {
        $request = $this->getRequest();
        $postData = $request->get('rooty_torrentbundle_typeformtype');
        $type = $postData['type'];
        
        if (!$type) {
            return $this->redirect($this->generateUrl('torrent_choose_type'));
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $typeEntity = $em->getRepository('RootyTorrentBundle:Type')->findOneBySlug($type);
        
        switch ($typeEntity->getSlug()) {
            case 'games':
                $entity = new Game();
                $form = $this->createForm(new GameFormType('new'), $entity);
                break;
            case 'movies':
                $entity = new Movie();
                $form = $this->createForm(new MovieFormType('new'), $entity);
                break;
        }
        
        $torrent = new Torrent();
        $torrent->setType($typeEntity);
        $entity->setTorrent($torrent);
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
     * Creates a new Torrent entity. 
     * 
     * @Route("/create", name="torrent_create")
     * @Method("post")
     * @Template("RootyTorrentBundle:Torrent:new.html.twig")
     */
    public function createAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $request = $this->getRequest();
        $postData = $request->get('rooty_torrentbundle_typeformtype');
        $type = $postData['type'];
        
        $typeEntity = $em->getRepository('RootyTorrentBundle:Type')->findOneBySlug($type);
        switch ($type) {
            case 'games':
                $entity = new Game();
                $torrent = new Torrent();
                $entity->setTorrent($torrent);
                $form = $this->createForm(new GameFormType('new'), $entity);
                break;
            case 'movies':
                $entity = new Movie();
                $torrent = new Torrent();
                $entity->setTorrent($torrent);
                $form = $this->createForm(new MovieFormType('new'), $entity);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity');
        }

        $form->bindRequest($request);
        if ($form->isValid()) {
            $typeEntity = $em->getRepository('RootyTorrentBundle:Type')->findOneBySlug($type);
            $entity->getTorrent()->setType($typeEntity);
            $entity->getTorrent()->setAddedBy($user);
            $entity->getTorrent()->setDateAdded(new \DateTime('now'));
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('torrent_show', array('id' => $entity->getTorrent()->getId())));
        }
        
        $torrent->setType($typeEntity);
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
     * Displays a form to edit an existing Torrent entity
     * 
     * @Route("/{id}/edit", name="torrent_edit")
     * @Template() 
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $type = $this->getTorrentType($id);
        switch ($type) {
            case 'games':
                $entity = $em->getRepository('RootyTorrentBundle:Game')->findOneByTorrent($id);
                $editForm = $this->createForm(new GameFormType('edit'), $entity);
                break;
            case 'movies':
                $entity = $em->getRepository('RootyTorrentBundle:Movie')->findOneByTorrent($id);
                $editForm = $this->createForm(new MovieFormType('edit'), $entity);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errors' => $editForm->getErrors(),
        );
    }
    
    /**
     * Edits an existing Torrent entity
     * @Route("/{id}/update", name="torrent_update")
     * @Method("post")
     * @Template("RootyTorrentBundle:Torrent:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $type = $this->getTorrentType($id);
        switch ($type) {
            case 'games':
                $entity = $em->getRepository('RootyTorrentBundle:Game')->findOneByTorrent($id);
                $editForm = $this->createForm(new GameFormType('edit'), $entity);
                break;
            case 'movies':
                $entity = $em->getRepository('RootyTorrentBundle:Movie')->findOneByTorrent($id);
                $editForm = $this->createForm(new MovieFormType('edit'), $entity);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        
        $request = $this->getRequest();
        $editForm->bindRequest($request);
        
        if ($editForm->isValid()) {
            $em->flush();
            
            return $this->redirect($this->generateUrl('torrent_show', array('id' => $entity->getTorrent()->getId())));
        }
        
        return array (
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errors' => $editForm->getErrors(),
        );
    }
    
    private function getTorrentType($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $query = $em->createQuery('SELECT type.slug FROM RootyTorrentBundle:Torrent t JOIN t.type type WHERE t.id = :id');
           
        $query->setParameter('id', $id);
        $result = $query->getSingleResult();
        
        return $result['slug'];
    }
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
