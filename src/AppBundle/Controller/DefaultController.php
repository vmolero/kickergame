<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $this->get('security.authorization_checker')->isGranted('ROLE_PLAYER') && ($path = '/players');
        $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($path = '/admin');

        return new RedirectResponse($request->getBaseUrl().$path);
    }

    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/players/", name="showGames")
     */
    public function playerHomepageAction(Request $request)
    {

        return $this->render('default/index.html.twig');

        // replace this example code with whatever you need
        // return $this->render('default/index.html.twig', array(
        //    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/", name="adminHomepage")
     */
    public function adminHomepageAction(Request $request)
    {

        return $this->render('default/index.html.twig');

        // replace this example code with whatever you need
        // return $this->render('default/index.html.twig', array(
        //    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //));
    }
}