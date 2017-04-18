<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class PlayerController extends Controller
{
    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/players", name="playersHomepage")
     */
    public function indexAction(Request $request)
    {

        !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') &&
        $this->redirect($request->getBaseUrl().'/login');

        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/players/games',
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/players/games", name="playerGames")
     * @Route("/players/{id}/games", name="specificPlayerGames")
     */
    public function gamesAction(Request $request, $id = null)
    {
        !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') &&
        $this->redirect($request->getBaseUrl().'/login');
        $gameRepo = $this->getDoctrine()
            ->getRepository('AppBundle:Game');
        $games = $id ? $gameRepo->findAllGamesByPlayer($id) : $gameRepo->findAll();

        return $this->render(
            'players/games.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'url' => $request->getBaseUrl().'/games',
                'games' => $games,
            ]
        );
    }

}