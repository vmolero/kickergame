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
        $r = $this->redirect($request->getBaseUrl().'/dashboard/');
        !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') &&
        ($r = $this->redirect($request->getBaseUrl().'/login'));

        return $r;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $handler = $this->get('app.role_handler');
        $render = $handler->getRender();
        var_dump($render);
        die;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function adminAction(Request $request)
    {
        return $this->render(
            'admin/index.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'playersUrl' => '/admin/players',
                'teamsUrl' => '/admin/teams',
                'gamesUrl' => '/admin/games',
            ]
        );
    }

}