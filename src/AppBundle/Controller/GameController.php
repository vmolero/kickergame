<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class GameController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @Route("/games/new/", name="newGame")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFormNewGameAction(Request $request)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle(
            'newGame',
            $request,
            [
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'userRepository' => $this->getDoctrine()->getRepository(User::REPOSITORY),
                'teamRepository' => $this->getDoctrine()->getRepository(Team::REPOSITORY),
                'gameRepository' => $this->getDoctrine()->getRepository(Game::REPOSITORY),
                'formFactory' => $this->get('form.factory'),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/games/", name="games")
     * @Route("/players/{id}/games/", name="specificPlayerGames")
     */
    public function showGamesAction(Request $request, $player_id = null)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle(
            'games',
            $request,
            [
                'id' => $player_id,
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'gameRepository' => $this->getDoctrine()->getRepository(Game::REPOSITORY),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @Route("/game/{id}/score/confirm", name="confirmGame")
     * @param Request $request
     * @param integer $id
     * @return ResponseRedirect
     */
    public function confirmGameAction(Request $request, $id)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle(
            'confirmGame',
            $request,
            [
                'id' => $id,
                'from' => $request->query->get('from'),
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'gameRepository' => $this->getDoctrine()->getRepository(Game::REPOSITORY),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @Route("/game/{id}/score/edit", name="editGameScore")
     * @param Request $request
     * @param integer $id
     * @return ResponseRedirect
     */
    public function editGameScoreAction(Request $request, $id)
    {

    }
}
