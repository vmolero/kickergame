<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StorableGame;
use AppBundle\Entity\Interfaces\TeamHolder;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 * @ORM\Table(name="game")
 */
class Game implements StorableGame
{
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
     * @ORM\Column(name="score_local", type="integer", nullable=true)
     */
    protected $scoreLocal;
    /**
     * @ORM\Column(name="score_visitor", type="integer", nullable=true)
     */
    protected $scoreVisitor;
    /**
     * @ORM\Column(name="when_played", type="datetime", nullable=true)
     */
    protected $whenPlayed;

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
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param mixed $local
     * @return Game
     */
    public function setLocal(TeamHolder $local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param mixed $visitor
     * @return Game
     */
    public function setVisitor(TeamHolder $visitor)
    {
        $this->visitor = $visitor;

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

    public function hasConflicts(TeamHolder $local, TeamHolder $visitor)
    {
        return in_array(
                $local->getPlayer1()->getUsername(),
                [$visitor->getPlayer1()->getUsername(), $visitor->getPlayer2()->getUsername()]
            ) ||
            in_array(
                $local->getPlayer2()->getUsername(),
                [$visitor->getPlayer1()->getUsername(), $visitor->getPlayer2()->getUsername()]
            );
    }
}