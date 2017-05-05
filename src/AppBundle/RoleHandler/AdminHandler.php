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
    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function dashboardAction(Request $request, array $data = [])
    {
        return $this->getResult(
            'admin/index.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'playersUrl' => '/players',
                'teamsUrl' => '/teams',
                'gamesUrl' => '/games',
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

        return $this->getResult(
            'admin/players.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
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

        return $this->getResult(
            'games/admin.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'games' => $games,
                'referrer' => $request->getRequestUri(),
                'canConfirm' => $canConfirm,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $data
     * @return array
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
        $messages = [];
        if ($game->isConfirmed()) {
            $messages['confirmation.already'] = 'Game already confirmed';
        } else {
            $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
            $gameRepo->save($game);
        }

        return $this->getRedirect($data['from']);
    }
}