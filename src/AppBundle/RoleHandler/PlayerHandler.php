<?php

namespace AppBundle\RoleHandler;

use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\GameType;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\TeamRepository;
use AppBundle\Repository\UserRepository;
use LogicException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerHandler extends RoleHandler
{
    public function dashboardAction(Request $request, array $data = [])
    {
        return $this->getResult(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

    public function playersAction(Request $request, array $data = [])
    {
        return $this->getResult(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

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
            'games/players.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'games' => $games,
                'referrer' => $request->getRequestUri(),
                'canConfirm' => $canConfirm,
            ]
        );
    }

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
        } else if (!$game->canBeConfirmedBy($data['user'])) {
            $messages['confirmation.notallowed'] = 'You\'re not allowed to confirm this game';
        } else {
            $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
            $gameRepo->save($game);
        }
        return $this->getRedirect($data['from'], $messages);
    }

}