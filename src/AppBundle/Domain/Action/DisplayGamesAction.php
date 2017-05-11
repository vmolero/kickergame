<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DisplayGamesAction
 * @package AppBundle\Domain\Action
 */
class DisplayGamesAction extends Action
{
    /**
     * @var GameRepository|null
     */
    private $gameRepo = null;
    /**
     * @var integer|null
     */
    private $playerId = null;

    /**
     * DisplayGamesAction constructor.
     * @param Request $request
     * @param GameRepository $repo
     * @param integer|null $playerId
     */
    public function __construct(Request $request, GameRepository $repo, $playerId = null)
    {
        parent::__construct($request);
        $this->gameRepo = $repo;
        $this->playerId = $playerId;
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitPlayer(KickerUserInterface $user)
    {
        if ($this->playerId !== null) {
            $gameCollection = $this->gameRepo->findAllTheGamesByPlayer($this->playerId);

            return $this->buildResponseFor($user, $gameCollection);
        }

        return $this->visitAdmin($user);
    }

    /**
     * @param KickerUserInterface $user
     * @param array $gameCollection
     * @return ActionResponse
     */
    private function buildResponseFor(KickerUserInterface $user, array $gameCollection)
    {
        $canConfirm = [];
        /** @var $aGame Game */
        foreach ($gameCollection as $aGame) {
            $aGame->canBeConfirmedBy($user) && ($canConfirm[$aGame->getId()] = 1);
        }

        return new ActionResponse(
            [
                'games' => $gameCollection,
                'referrer' => $this->playerId,
                'canConfirm' => $canConfirm,
            ]
        );
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitAdmin(KickerUserInterface $user)
    {
        $gameCollection = $this->gameRepo->findAll();

        return $this->buildResponseFor($user, $gameCollection);
    }
}