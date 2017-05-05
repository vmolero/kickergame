<?php

namespace AppBundle\RoleHandler;

use AppBundle\Controller\GameController;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use LogicException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerHandler
 * @package AppBundle\RoleHandler
 */
class PlayerHandler extends RoleHandler
{
    const DASHBOARD = 'players/index.html.twig';
    const PLAYERS = 'players/index.html.twig';
    const GAMES = 'games/players.html.twig';

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
        return $this->setResult(
            self::PLAYERS,
            [
                'url' => $request->getBaseUrl().'/games',
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
            throw new \LogicException('Missing data keys');
        }
        /* @var $gameRepo GameRepository */
        $gameRepo = $data['gameRepository'];
        /* @var $game Game */
        $game = $gameRepo->find($data['id']);
        $messages = [];
        if ($game->isConfirmed()) {
            $messages['confirmation.already'] = 'Game already confirmed';
        } else {
            if (!$game->canBeConfirmedBy($data['user'])) {
                $messages['confirmation.notallowed'] = 'You\'re not allowed to confirm this game';
            } else {
                $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
                $gameRepo->save($game);
            }
        }

        return $this->setRedirect(GameController::GAME_ROUTE_NAME, $messages);
    }

}