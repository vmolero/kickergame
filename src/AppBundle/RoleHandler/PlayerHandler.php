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
        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

    public function playersAction(Request $request, array $data = [])
    {
        return $this->render(
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

        return $this->render(
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
        /** @var GameRepository $gameRepo */
        $gameRepo = $data['gameRepository'];
        /* @var $game Game */
        /** @var Game $game */
        $game = $gameRepo->find($data['id']);
        if ($game->isConfirmed()) {
            $this->getSession()->getFlashBag()->set('confirmation.already', 'Game already confirmed');
        } else if (!$game->canBeConfirmedBy($data['user'])) {
            $this->getSession()->getFlashBag()->set('confirmation.notallowed', 'You\'re not allowed to confirm this game');
        } else {
            $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();
        }
        return new RedirectResponse(urldecode($data['from']));
    }
    
    public function newGameAction(Request $request, array $data = [])
    {
        if ($this->invalidNewGameAction($data)) {
            throw new LogicException('Missing data keys');
        }
        /** @var Game $game */
        $game = new Game();
        /** @var User $user */
        $user = $data['user'];
        /** @var UserRepository $userRepository */
        $userRepository = $data['userRepository'];
        /** @var TeamRepository $teamRepository */
        $teamRepository = $data['teamRepository'];
        /** @var GameRepository $gameRepository */
        $gameRepository = $data['gameRepository'];
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $data['formFactory'];
        /** @var FormInterface $form */
        $form = $formFactory->create(
            GameType::class,
            $game,
            [
                'players' => $userRepository->findByRole(Role::PLAYER),
            ]
        )->add('save', SubmitType::class, array('label' => 'Create Game'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Game $game */
            $game = $form->getData();
            $game->setCreatedBy($user);
            if ($game->hasConflicts()) {
                $this->getSession()->getFlashBag()->set('registrationMessage', 'Game has team conflicts');
            } else {
                if ($user->hasRole(Role::PLAYER) && !$game->hasPlayer($user)) {
                    $this->getSession()->getFlashBag()->set('registrationMessage', 'You must be part of the game');
                } else {
                    $game = $teamRepository->useExistingTeamsFor($game);
                    $game->setStatus(Game::OPEN);
                    $gameRepository->save($game);
                    $this->getSession()->getFlashBag()->set('registrationMessage', 'Game saved');
                }
            }
        }

        return $this->render(
            'games/new.html.twig',
            array(
                'form' => $form->createView(),
                'baseUrl' => $request->getBaseUrl(),
            )
        );
    }

}