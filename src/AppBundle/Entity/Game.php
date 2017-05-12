<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StorableGame;
use AppBundle\Entity\Interfaces\TeamHolder;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 * @ORM\Table(name="game")
 */
class Game implements StorableGame
{
    const OPEN = 1;
    const CLOSED = 0;
    const REPOSITORY = 'AppBundle:Game';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Team", cascade={"persist"})
     * @ORM\JoinColumn(name="local", referencedColumnName="id")
     */
    protected $local;
    /**
     * @ORM\ManyToOne(targetEntity="Team", cascade={"persist"})
     * @ORM\JoinColumn(name="visitor", referencedColumnName="id")
     */
    protected $visitor;
    /**
     * @Assert\Range(min=0, max=10)
     * @ORM\Column(name="score_local", type="integer", nullable=true)
     */
    protected $scoreLocal;
    /**
     * @Assert\Range(min=0, max=10)
     * @ORM\Column(name="score_visitor", type="integer", nullable=true)
     */
    protected $scoreVisitor;
    /**
     * @ORM\Column(name="when_played", type="datetime", nullable=true)
     */
    protected $whenPlayed;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     */
    protected $createdBy;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
     * @ORM\JoinColumn(name="confirmed_by", referencedColumnName="id")
     */
    protected $confirmedBy;

    /**
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"default": 1,
     *                                                             "comment":"Status of the game 0 Closed, 1 Open"})
     */
    protected $status = self::OPEN;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->whenPlayed = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Game
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScoreLocal()
    {
        return $this->scoreLocal;
    }

    /**
     * @param mixed $scoreLocal
     * @return Game
     */
    public function setScoreLocal($scoreLocal)
    {
        $this->scoreLocal = $scoreLocal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScoreVisitor()
    {
        return $this->scoreVisitor;
    }

    /**
     * @param mixed $scoreVisitor
     * @return Game
     */
    public function setScoreVisitor($scoreVisitor)
    {
        $this->scoreVisitor = $scoreVisitor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhenPlayed()
    {
        return $this->whenPlayed;
    }

    /**
     * @param mixed $whenPlayed
     * @return Game
     */
    public function setWhenPlayed(DateTime $whenPlayed)
    {
        $this->whenPlayed = $whenPlayed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Game
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function hasConflicts()
    {
        return $this->visitor->hasConflicts() ||
            $this->local->hasConflicts() ||
            $this->isThereAPlayerInBothTeams();
    }

    private function isThereAPlayerInBothTeams()
    {
        return $this->local->hasPlayer($this->visitor->getPlayer1()) ||
            $this->local->hasPlayer($this->visitor->getPlayer2()) ||
            $this->visitor->hasPlayer($this->local->getPlayer1()) ||
            $this->visitor->hasPlayer($this->local->getPlayer2());
    }

    public function canBeConfirmedBy(UserInterface $player)
    {
        if ($this->isConfirmed()) {
            return false;
        }
        if ($player->hasRole(Role::ADMIN)) {
            return true;
        }

        return $this->isConfirmingPlayerAnOpponent($player);
    }

    public function isConfirmed()
    {
        return $this->getConfirmedBy() instanceof UserInterface;
    }

    /**
     * @return UserInterface
     */
    public function getConfirmedBy()
    {
        return $this->confirmedBy;
    }

    /**
     * @param UserInterface $confirmedBy
     * @return Game
     */
    public function setConfirmedBy(UserInterface $confirmedBy)
    {
        $this->confirmedBy = $confirmedBy;

        return $this;
    }

    private function isConfirmingPlayerAnOpponent(UserInterface $confirming)
    {
        /** @var User $userWhoCreated */
        $gameCreator = $this->getCreatedBy();

        return $this->getLocal()->hasPlayer($confirming) && $this->getVisitor()->hasPlayer($gameCreator) ||
            $this->getVisitor()->hasPlayer($confirming) && $this->getLocal()->hasPlayer($gameCreator);
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     * @return Game
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return TeamHolder
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param TeamHolder $local
     * @return Game
     */
    public function setLocal(TeamHolder $local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * @return TeamHolder
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param TeamHolder $visitor
     * @return Game
     */
    public function setVisitor(TeamHolder $visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * @param UserInterface $player
     * @return bool
     */
    public function hasPlayer(UserInterface $player)
    {
        return $this->local->hasPlayer($player) || $this->visitor->hasPlayer($player);
    }
}
