<?php

namespace AppBundle\ServiceLayer;

use AppBundle\Domain\Action\Action;
use AppBundle\Domain\Admin;
use AppBundle\Domain\Player;
use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\GameType;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\TeamRepository;
use AppBundle\Repository\UserRepository;
use LogicException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class RoleHandler implements RoleInterface
{
    /**
     * @var Admin|Player
     */
    private $user;
    private $template;
    private $redirectTo;
    /**
     * @var ActionResponse
     */
    private $actionResponse;

    /**
     * RoleHandler constructor.
     * @param AuthorizationCheckerInterface $checker
     * @param TokenStorageInterface $storage
     */
    public function __construct(AuthorizationCheckerInterface $checker, TokenStorageInterface $storage)
    {
        $user = new Player($storage->getToken()->getUser());
        $checker->isGranted(Role::ADMIN) && ($user = new Admin($user));
        $this->user = $user;
    }

    public function handle(Action $action, array $parameters)
    {
        if (!isset($parameters[$this->getRoleTemplateParameter()])) {
            throw new LogicException('YOu must provide a template for the role');
        }
        $this->template = $parameters[$this->getRoleTemplateParameter()];
        $this->actionResponse = $action->visit($this->getUser());
    }

    private function getRoleTemplateParameter()
    {
        return strtolower(substr($this->getRole(), 5));
    }

    public function getRole()
    {
        return $this->user->getRole();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->actionResponse->getMessages();
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->actionResponse->getParameters();
    }

    /**
     * @return mixed
     */
    public function getRedirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * @param mixed $redirectTo
     * @return RoleHandler
     */
    protected function setRedirectTo($redirectTo)
    {
        $this->redirectTo = $redirectTo;

        return $this;
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
        $messages = [];
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

        return $this->setResult(
            'games/new.html.twig',
            [
                'form' => $form->createView(),
                'baseUrl' => $request->getBaseUrl(),
            ],
            $messages
        );
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

    /**
     * @param $view
     * @param array $parameters
     * @param array $messages
     * @return array
     */
    protected function setResult($view, array $parameters = [], array $messages = [])
    {
        $this->setTemplate($view);
        $this->setParameters($parameters);
        $this->setMessages($messages);

        return $this;
    }

    /**
     * @param $routeName
     * @param array $messages
     * @return array
     */
    protected function setRedirect($routeName, array $messages = [])
    {
        $this->setRedirectTo($routeName);
        $this->setMessages($messages);

        return $this;
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
}