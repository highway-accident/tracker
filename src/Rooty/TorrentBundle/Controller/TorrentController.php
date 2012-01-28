<?php

namespace Rooty\TorrentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Rooty\TorrentBundle\Entity\Torrent;
use Rooty\TorrentBundle\Entity\Game;
use Rooty\TorrentBundle\Form\Type\TorrentFormType;

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
        return $this->render('RootyTorrentBundle:Torrent:index.html.twig');
    }

    /**
    * Displays a form to create a new Torrent entity.
    * @Route("/new", name="torrent_new")
    * @Template()
    */
    public function newAction()
    {
        $request = Request::createFromGlobals();

        if ($request->request->get('category')) {
            echo $request->request->get('category');
        } else {
            
        }
        
        $entity = new Torrent();
        $form = $this->createForm(new TorrentFormType('game'), $entity);
        
        return array(
            'entity' => $entity,
            'form' => $form->createView()
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity = new Torrent();
        $request = $this->getRequest();
        $postData = $request->get('rooty_torrentbundle_torrentformtype');
        $type = $postData['type'];
        $form = $this->createForm(new TorrentFormType($type), $entity);
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $entity->setType($type);
            $entity->setAddedBy($user);
            $entity->setDateAdded(new \DateTime('now'));
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            //$em->flush();
            
            switch ($type) {
                case 'game':
                    $entity2 = new Game();
                    $entity2->setTorrent($entity);
                    $entity2->setGenre($postData['genre']);
                    $entity2->setDeveloper($postData['developer']);
                    $entity2->setPublisher($postData['publisher']);
                    $entity2->setSystemRequirements($postData['system_requirements']);
                    $entity2->setCrackUrl($postData['crack_url']);
                    $entity2->setHowToRun($postData['how_to_run']);
                    $em->persist($entity2);
                    $em->flush();
                    break;
                default:
                    throw new \Exception('Wrong torrent type!');
                    break;
            }
            return $this->redirect($this->generateUrl('torrents'));
        }
        
        return array(
            'entity' => $entity,
            'form' => $form->createView()
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
        
        $data = array(); //custom type fields values
        $type = $this->getTorrentType($id);
        switch ($type) {
            case 'game':
                $entity = $em->getRepository('RootyTorrentBundle:Game')->findOneByTorrent($id);
                $data['genre'] = $entity->getGenre();
                $data['developer'] = $entity->getDeveloper();
                $data['publisher'] = $entity->getPublisher();
                $data['system_requirements'] = $entity->getSystemRequirements();
                $data['crack_url'] = $entity->getCrackUrl();
                $data['how_to_run'] = $entity->getHowToRun();
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity');
        }
        
        $editForm = $this->createForm(new TorrentFormType($type, $data), $entity->getTorrent());
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
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
            case 'game':
                $entity = $em->getRepository('RootyTorrentBundle:Game')->findOneByTorrent($id);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Torrent entity');
        }
        
        $editForm = $this->createForm(new TorrentFormType($type, array()), $entity->getTorrent());
        $deleteForm = $this->createDeleteForm($id);
        
        $request = $this->getRequest();
        
        $editForm->bindRequest($request);
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('torrents'));
        }
        
        return array (
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }
    
    private function getTorrentType($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $query = $em->createQuery('SELECT t.type FROM RootyTorrentBundle:Torrent t WHERE t.id = :id');
        $query->setParameter('id', $id);
        $result = $query->getSingleResult();
        
        return $result['type'];
    }
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
