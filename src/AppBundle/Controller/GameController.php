<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\DisplayGamesAction;
use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameController
 * @package AppBundle\Controller
 */
class GameController extends KickerController
{
    const GAME_ROUTE_NAME = 'games';
    const SPECIFIC_PLAYER_GAME_ROUTE_NAME = 'specificPlayerGames';

    /**
     * @CFG\Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/new/", name="newGame")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFormNewGameAction(Request $request)
    {
        /* @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        $handler->newGameAction(
            $request,
            [
                'user' => $this->container->get('security.token_storage')->getToken()->getUser(),
                'userRepository' => $this->getDoctrine()->getRepository(User::REPOSITORY),
                'teamRepository' => $this->getDoctrine()->getRepository(Team::REPOSITORY),
                'gameRepository' => $this->getDoctrine()->getRepository(Game::REPOSITORY),
                'formFactory' => $this->get('form.factory'),
            ]
        );

        return $this->buildResponse($handler);
    }

    /**
     * @CFG\Security("has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/", name="games")
     * @CFG\Route("/players/{id}/games/", name="specificPlayerGames")
     */
    public function showGamesAction(Request $request, $id = null)
    {
        $handler = $this->get('app.role_handler');
        $handler->handle(
            new DisplayGamesAction($request, $this->getDoctrine()->getRepository(Game::REPOSITORY), $id),
            $this->getParameter('template.games')
        );

        return $this->buildResponse($handler);
    }

    /**
     * @CFG\Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/{id}/score/confirm", name="confirmGame")
     * @param Request $request
     * @param integer $id
     * @return ResponseRedirect
     */
    public function confirmGameAction(Request $request, $id)
    {
        /* @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        /* @var $user User */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $referrer = $request->query->get('from');
        $handler->handle(
            'confirmGame',
            $request,
            [
                'id' => $id,
                'user' => $user,
                'gameRepository' => $this->getDoctrine()->getRepository(Game::REPOSITORY),
            ]
        );
        $this->fillFlashBag($handler->getMessages());
        if (is_numeric($referrer)) {
            return $this->redirectToRoute('specificPlayerGames', ['id' => $referrer]);
        }

        return $this->redirectToRoute('games');
    }
}
