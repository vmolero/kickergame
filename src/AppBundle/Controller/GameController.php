<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\ConfirmGameAction;
use AppBundle\Domain\Action\DisplayGamesAction;
use AppBundle\Domain\Action\DisplayGamesFormAction;
use AppBundle\Entity\Game;
use AppBundle\ServiceLayer\RenderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GameController
 * @package AppBundle\Controller
 */
class GameController extends Controller
{
    /**
     * @CFG\Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/new/", name="newGame")
     *
     * @param Request $request
     * @return Response
     */
    public function showFormNewGameAction(Request $request)
    {
        /** @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        $handler->handle(
            new DisplayGamesFormAction(
                $request,
                $this->getDoctrine()->getRepository(Game::REPOSITORY),
                $this->get('app.game_form_provider')
            )
        );

        /** @var $render RenderService */
        $render = $this->get('app.render');

        return $render->setTemplate($this->getParameter('template.newgame'))
            ->buildResponse($handler);
    }

    /**
     * @CFG\Security("has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/", name="games")
     * @CFG\Route("/players/{id}/games/", name="specificPlayerGames")
     *
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function showGamesAction(Request $request, $id = null)
    {
        /** @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        $handler->handle(
            new DisplayGamesAction(
                $request,
                $this->getDoctrine()->getRepository(Game::REPOSITORY),
                $id
            )
        );

        /* @var $render RenderService */
        $render = $this->get('app.render');

        return $render->setTemplate($this->getParameter('template.games'))
            ->buildResponse($handler);
    }

    /**
     * @CFG\Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @CFG\Route("/games/{id}/score/confirm", name="confirmGame")
     *
     * @param Request $request
     * @param integer $id
     * @return ResponseRedirect
     */
    public function confirmGameAction(Request $request, $id)
    {
        /* @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        $referrer = $request->query->get('from');
        $handler->handle(
            new ConfirmGameAction($request, $this->getDoctrine()->getRepository(Game::REPOSITORY), $id)
        );
        /* @var $render RenderService */
        $render = $this->get('app.render');
        $render->fillFlashBag($handler->getMessages());
        if (is_numeric($referrer)) {
            return $this->redirectToRoute('specificPlayerGames', ['id' => $referrer]);
        }

        return $this->redirectToRoute('games');
    }
}
