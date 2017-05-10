<?php

namespace AppBundle\Domain\Action;


use AppBundle\Entity\Game;
use AppBundle\Repository\GameRepository;
use FOS\UserBundle\Model\UserInterface;
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
     * @param UserInterface $user
     * @return ActionResponse
     */
    public function visitPlayer(UserInterface $user)
    {
        if ($this->playerId !== null) {
            $gameCollection = $this->gameRepo->findAllTheGamesByPlayer($this->playerId);

            return $this->buildResponseFor($user, $gameCollection);
        }

        return $this->visitAdmin($user);
    }

    /**
     * @param UserInterface $user
     * @param array $gameCollection
     * @return ActionResponse
     */
    private function buildResponseFor(UserInterface $user, array $gameCollection)
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
     * @param UserInterface $user
     * @return ActionResponse
     */
    public function visitAdmin(UserInterface $user)
    {
        $gameCollection = $this->gameRepo->findAll();

        return $this->buildResponseFor($user, $gameCollection);
    }
}