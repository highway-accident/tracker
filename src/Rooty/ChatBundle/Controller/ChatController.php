<?php

namespace Rooty\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Rooty\ChatBundle\Entity\Message;
use Rooty\ChatBundle\Form\Type\MessageFormType;
use Rooty\DefaultBundle\Extension\UserlinkExtension;

/**
* Chat controller
*
* @Route("/chat")
*/
class ChatController extends Controller
{
    /**
     * @Route("/", name="chat")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('RootyChatBundle:Message')->findBy(array(), array('date_added' => 'DESC'), 30);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $userlinkExtension = new UserlinkExtension($this);
            foreach ($entity as $message) {
                $path = $userlinkExtension->userlinkFilter($message->getAddedBy());
                $messages[] = array(
                    'dateAdded' => $message->getDateAdded()->format('H:i'),
                    'path' => $path,
                    'text' => htmlspecialchars($message->getText()),
                );
            }
                    
            return new Response(json_encode(array(
                'status' => 'ok', 
                'error' => array(),
                'messages' => $messages,
            )));
        }
        
        $entity = new Message();
        $form = $this->createForm(new MessageFormType(), $entity);
        
        return array(
            'messages' => $entity,
            'form' => $form->createView(),
        );
    }
    
    /**
     * Create a new Chat Message entity
     * @Route("/create", name="chat_message_create")
     * @Method("post")
     * @Secure(roles="ROLE_USER")
     */
    public function createAction()
    {
        $message = new Message();
        $form = $this->createForm(new MessageFormType(), $message);
        $request = $this->getRequest();
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            
            $message->setAddedBy($user);
            $message->setDateAdded(new \DateTime('now'));
            
            $em->persist($message);
            $em->flush();
        } else {
            echo 'error';
        }
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            return new Response(json_encode(array(
                'status' => 'ok', 
                'error' => array(),
            )));
        }
        
        return $this->redirect($this->generateUrl('chat'));
    }
}
