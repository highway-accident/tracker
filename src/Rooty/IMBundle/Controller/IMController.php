<?php

namespace Rooty\IMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Rooty\IMBundle\Entity\Message;
use Rooty\IMBundle\Form\Type\MessageFormType;

/**
* IM controller
*
* @Route("/im")
*/
class IMController extends Controller
{
    /**
     * Show all current user messages
     * @Route("/", name="im")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $inbox = $em->getRepository('Rooty\IMBundle\Entity\Message')->findBy(array('recepient' => $user->getId()), array('dateAdded' => 'DESC'));
        $sent = $em->getRepository('Rooty\IMBundle\Entity\Message')->findBy(array('sender' => $user->getId()), array('dateAdded' => 'DESC'));
        
        return array(
            'inbox' => $inbox,
            'sent' => $sent,
        );
    }
    
    /**
     * Finds and displays a Message entity
     * 
     * @Route("/{id}/show", name="message_show")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyIMBundle:Message')->findOneById($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }
        
        $reply = new Message();
        $form = $this->createForm(new MessageFormType(), $reply);
        
        return array(
            'form' => $form->createView(),
            'message' => $entity,
        );
    }
    
    /**
     * Displays a form to create a new Message entity.
     * @Route("/{recepient_id}/new", name="message_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newAction($recepient_id) {
        $entity = new Message();
        $form = $this->createForm(new MessageFormType(), $entity);
        $em = $this->getDoctrine()->getEntityManager();
        $recepient = $em->getRepository('RootyUserBundle:User')->findOneById($recepient_id);

        return array(
            'recepient' => $recepient,
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }
    
    /**
     * Creates a new Message entity. 
     * 
     * @Route("/{recepient_id}/create", name="message_create")
     * @Template("RootyIMBundle:Message:new.html.twig")
     * @Method("post")
     * @Secure(roles="ROLE_USER")
     */
    public function createAction($recepient_id) {
        $entity = new Message();
        $form = $this->createForm(new MessageFormType(), $entity);
        $request = $this->getRequest();
        
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $sender = $this->container->get('security.context')->getToken()->getUser();
            $recepient = $em->getRepository('RootyUserBundle:User')->findOneById($recepient_id);
            
            $entity->setSender($sender);
            $entity->setRecepient($recepient);
            $entity->setDateAdded(new \DateTime('now'));
            
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('im').'#tab#sent');
        }
        return;
    }
}
