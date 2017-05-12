<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DashboardAction
 * @package AppBundle\Domain\Action
 */
class ConfirmGameAction extends Action
{
    /**
     * @var GameRepository
     */
    private $repo;
    /**
     * @var integer
     */
    private $id;

    /**
     * ConfirmGameAction constructor.
     * @param Request $request
     * @param GameRepository $gameRepo
     * @param $id
     */
    public function __construct(Request $request, GameRepository $gameRepo, $id)
    {
        parent::__construct($request);
        $this->repo = $gameRepo;
        $this->id = $id;
    }

    /**
     * @param KickerUserInterface $user
     * @return NullActionResponse
     */
    public function visitPlayer(KickerUserInterface $user)
    {
        return $this->confirmAction($user);
    }

    /**
     * @param KickerUserInterface $user
     * @return NullActionResponse
     */
    public function visitAdmin(KickerUserInterface $user)
    {
        return $this->confirmAction($user);
    }

    protected function confirmAction(KickerUserInterface $user)
    {
        /* @var $game Game */
        $game = $this->repo->find($this->id);
        if ($game->isConfirmed()) {
            return new ActionResponse([], ['confirmation.already' => 'Game already confirmed']);
        }
        if (!$game->canBeConfirmedBy($user)) {
            return new ActionResponse([], ['confirmation.notallowed' => 'You\'re not allowed to confirm this game']);
        }
        $game->setConfirmedBy($user->getEntity())->setStatus(Game::CLOSED);
        $this->repo->save($game);

        return new ActionResponse([], ['confirmation.success' => 'Game confirmed']);
    }
}