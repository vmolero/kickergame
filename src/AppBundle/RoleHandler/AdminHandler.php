<?php

namespace AppBundle\RoleHandler;

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
    const DASHBOARD = 'admin/index.html.twig';
    const PLAYERS = 'admin/players.html.twig';
    const GAMES = 'games/admin.html.twig';

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function dashboardAction(Request $request, array $data = [])
    {
        return $this->setResult(
            self::DASHBOARD,
            [
                'registerUrl' => RoleHandler::REGISTER_URL,
                'playersUrl' => RoleHandler::PLAYERS_URL,
                'teamsUrl' => RoleHandler::TEAMS_URL,
                'gamesUrl' => RoleHandler::GAMES_URL,
            ]
        );
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
            self::PLAYERS,
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
            self::GAMES,
            [
                'games' => $games,
                'referrer' => $data['referrer'],
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
        if ($this->invalidConfirmGameAction($data)) {
            throw new LogicException('Missing data keys');
        }
        /** @var GameRepository $gameRepo */
        $gameRepo = $data['gameRepository'];
        /** @var Game $game */
        $game = $gameRepo->find($data['id']);
        $messages = ['confirmation.action' => 'Game confirmed and closed'];
        if ($game->isConfirmed()) {
            $messages['confirmation.action'] = 'Game already confirmed';

            return $this->setRedirect('games', $messages);
        }
        $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
        $gameRepo->save($game);

        return $this->setRedirect('games', $messages);
    }
}