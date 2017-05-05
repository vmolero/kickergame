<?php

namespace AppBundle\RoleHandler;

use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\GameType;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\TeamRepository;
use AppBundle\Repository\UserRepository;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\Request;

abstract class RoleHandler
{
    /**
     * @param $name
     * @param Request $request
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function handle($name, Request $request, array $data = [])
    {
        try {
            return $this->{$name.'Action'}($request, $data);
        } catch (Exception $m) {
            throw $m;
        }
    }

    /**
     * @param $view
     * @param array $parameters
     * @param array $messages
     * @return array
     */
    protected function getResult($view, array $parameters = [], array $messages = [])
    {
        return [
            'template' => $view,
            'parameters' => $parameters,
            'messages' => $messages
        ];
    }

    /**
     * @param $url
     * @return array
     */
    protected function getRedirect($url, array $messages = [])
    {
        return [
            'url' => $url,
            'messages' => $messages
        ];
    }
   
    /**
     * 
     * @param Request $request
     * @param array $data
     * @return type
     * @throws LogicException
     */
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
        )->add('save', SubmitType::class, ['label' => 'Create Game']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messages = [];
            /** @var Game $game */
            $game = $form->getData();
            $game->setCreatedBy($user);
            if ($game->hasConflicts()) {
                $messages[] = ['alert' => 'Game has team conflicts'];
                // $this->getSession()->getFlashBag()->set('registrationMessage', 'Game has team conflicts');
            } else {
                if ($user->hasRole(Role::PLAYER) && !$game->hasPlayer($user)) {
                    $messages[] = ['alert' => 'You must be part of the game'];
                } else {
                    $game = $teamRepository->useExistingTeamsFor($game);
                    $game->setStatus(Game::OPEN);
                    $gameRepository->save($game);
                    $messages[] = ['alert' => 'Game saved'];
                }
            }
        }

        return $this->getResult('games/new.html.twig',
            [
                'form' => $form->createView(),
                'baseUrl' => $request->getBaseUrl(),
            ], 
            $messages
        );
    }
    
    protected function invalidPlayersAction(array $data)
    {
        return !(array_key_exists('userRepository', $data));
    }
    
    protected function invalidGamesAction(array $data)
    {
        return !(array_key_exists('id', $data) &&
            array_key_exists('gameRepository', $data) &&
            array_key_exists('user', $data));
    }
    
    protected function invalidConfirmGameAction(array $data)
    {
        return $this->invalidGamesAction($data) ||
            !array_key_exists('from', $data);
    }
    
    protected function invalidNewGameAction(array $data)
    {
        return
            !(array_key_exists('userRepository', $data) &&
                array_key_exists('teamRepository', $data) &&
                array_key_exists('gameRepository', $data) &&
                array_key_exists('user', $data) &&
                array_key_exists('formFactory', $data));
    }
}