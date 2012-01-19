<?php

namespace Rooty\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('RootyDefaultBundle:Default:index.html.twig');
    }
}
