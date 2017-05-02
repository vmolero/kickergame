<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class KickerController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $r = $this->redirect($request->getBaseUrl().'/dashboard/');
        !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') &&
        ($r = $this->redirect($request->getBaseUrl().'/login'));

        return $r;
    }

    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/dashboard/", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle('dashboard', $request);
    }



}