<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AccessController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $path = '/login';
        $this->get('security.authorization_checker')->isGranted('ROLE_PLAYER') && ($path = '/players');
        $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($path = '/admin');

        return new RedirectResponse($request->getBaseUrl().$path);
    }

}