<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tvg_game")
 */
class Game
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
     * @ORM\Column(name="score_local", type="integer", options={"default" : 0})
     */
    protected $scoreLocal;
    /**
     * @ORM\Column(name="score_visitor", type="integer", options={"default" : 0})
     */
    protected $scoreVisitor;
    /**
     * @ORM\Column(name="when_played", type="date", nullable=true)
     */
    protected $whenPlayed;
}