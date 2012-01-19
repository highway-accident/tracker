<?php

namespace Rooty\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('RootyUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
