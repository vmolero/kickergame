<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Game;
use AppBundle\Entity\Interfaces\StorableGame;
use AppBundle\Entity\Role;
use AppBundle\Repository\GameRepository;
use AppBundle\ServiceLayer\GameFormProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DisplayGamesAction
 * @package AppBundle\Domain\Action
 */
class DisplayGamesFormAction extends Action
{
    /**
     * @var GameRepository|null
     */
    private $gameRepo = null;

    /**
     * @var FormFactoryInterface|null
     */
    private $formProvider = null;


    public function __construct(
        Request $request,
        GameRepository $gameRepo,
        GameFormProvider $formProvider
    ) {
        parent::__construct($request);
        $this->gameRepo = $gameRepo;
        $this->formProvider = $formProvider;
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitAdmin(KickerUserInterface $user)
    {
        return $this->visitPlayer($user);
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitPlayer(KickerUserInterface $user)
    {
        $this->formProvider->createGameForm('Create Game');
        /** @var Game $game */
        $game = $this->formProvider->buildGameEntity($this->request);
        $formView = ['form' => $this->formProvider->createView()];
        if (!$game instanceof StorableGame) {
            return new ActionResponse($formView);
        }
        if ($game->hasConflicts()) {
            return new ActionResponse($formView, ['alert' => 'Game has team conflicts']);
        }
        if ($user->hasRole(Role::PLAYER) && !$game->hasPlayer($user)) {
            return new ActionResponse($formView, ['alert' => 'You must be part of the game']);
        }
        $game->setStatus(Game::OPEN);
        $game->setCreatedBy($user->getEntity());
        $this->gameRepo->save($game);

        return new ActionResponse($formView, ['info' => 'Game saved']);
    }
}