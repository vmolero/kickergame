<?php

namespace AppBundle\RoleHandler;

use AppBundle\Controller\GameController;
use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\UserRepository;
use LogicException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminHandler
 * @package AppBundle\RoleHandler
 */
class AdminHandler extends RoleHandler
{
    const DASHBOARD_TPL = 'admin/index.html.twig';
    const PLAYERS_TPL = 'admin/players.html.twig';
    const GAMES_TPL = 'games/admin.html.twig';

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function dashboardAction(Request $request, array $data = [])
    {
        return $this->setResult(self::DASHBOARD_TPL);
    }

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function playersAction(Request $request, array $data = [])
    {
        if ($this->invalidPlayersAction($data)) {
            throw new LogicException('Missing data keys');
        }
        /** @var UserRepository $userRepository */
        $userRepository = $data['userRepository'];
        $players = $userRepository->findByRole(Role::PLAYER);

        return $this->setResult(
            self::PLAYERS_TPL,
            [
                'dashboardUrl' => RoleHandler::DASHBOARD_URL,
                'registerUrl' => RoleHandler::REGISTER_URL,
                'players' => $players,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function gamesAction(Request $request, array $data = [])
    {
        if ($this->invalidGamesAction($data)) {
            throw new LogicException('Missing data keys');
        }
        /** @var GameRepository $gameRepo */
        $gameRepo = $data['gameRepository'];
        $games = isset($data['id']) ? $gameRepo->findAllTheGamesByPlayer($data['id']) : $gameRepo->findAll();
        $canConfirm = [];
        foreach ($games as $aGame) {
            $aGame->canBeConfirmedBy($data['user']) && ($canConfirm[$aGame->getId()] = 1);
        }

        return $this->setResult(
            self::GAMES_TPL,
            [
                'games' => $games,
                'referrer' => isset($data['id']) ? $data['id'] : null,
                'canConfirm' => $canConfirm,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $data
     * @return boolean
     */
    public function confirmGameAction(Request $request, array $data = [])
    {
        if (false && $this->invalidGamesAction($data)) {
            throw new LogicException('Missing data keys');
        }
        /** @var GameRepository $gameRepo */
        $gameRepo = $data['gameRepository'];
        /** @var Game $game */
        $game = $gameRepo->find($data['id']);
        $messages = ['confirmation.action' => 'Game confirmed and closed'];
        if ($game->isConfirmed()) {
            $messages['confirmation.action'] = 'Game already confirmed';

            return $this->setRedirect(GameController::GAME_ROUTE_NAME, $messages);
        }
        $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
        $gameRepo->save($game);

        return $this->setRedirect(GameController::GAME_ROUTE_NAME, $messages);
    }
}