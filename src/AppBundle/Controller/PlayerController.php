<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/players/", name="playersHomepage")
     */
    public function indexAction(Request $request)
    {

        !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') &&
            $this->redirect($request->getBaseUrl().'/login');
        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

}