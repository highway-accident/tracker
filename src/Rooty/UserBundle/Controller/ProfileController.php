<?php

namespace Rooty\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Rooty\UserBundle\Entity\User;
use Rooty\UserBundle\Form\Type\ProfileFormType;

/**
* Profile controller
*
* @Route("/profile")
*/
class ProfileController extends Controller
{
    /**
     * Displays a user profile
     * 
     * @Route("/", name="profile")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        return array(
            'user' => $user,
        );
    }
    
    /**
     * Displays a form to edit user profile
     * 
     * @Route("/edit", name="profile_edit")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function editAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $editForm = $this->createForm(new ProfileFormType(), $user);
        
        return array(
            'user' => $user,
            'form' => $editForm->createView(),
            'errors' => $editForm->getErrors(),
        );
    }
    
    /**
     * Edits user profile
     * @Route("/update", name="profile_update")
     * @Method("post")
     * @Template("RootyUserBundle:Profile:edit.html.twig")
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $editForm = $this->createForm(new ProfileFormType(), $user);
        
        $request = $this->getRequest();
        $editForm->bindRequest($request);
        
        if ($editForm->isValid()) {
            $em->flush();
            
            $this->get('session')->setFlash('notice', 'Ваши изменения успешно сохранены!');
            
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return array (
            'user' => $user,
            'form' => $editForm->createView(),
            'errors' => $editForm->getErrors(),
        );
    }
}
