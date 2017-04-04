<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return new Response('Default response');

        // replace this example code with whatever you need
        // return $this->render('default/index.html.twig', array(
        //    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //));
    }

    /**
     * @Route("/admin/login", name="adminLogin")
     */
    public function adminLoginAction(Request $request)
    {
        return new Response('Admin Login');
    }

    /**
     * @Route("/player/login", name="playerLogin")
     */
    public function playerLoginAction(Request $request)
    {
        return new Response('PLayer Login');
    }
}
