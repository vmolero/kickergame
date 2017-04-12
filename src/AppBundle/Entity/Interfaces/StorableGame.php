<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/12/17
 * Time: 10:07 AM
 */

namespace AppBundle\Entity\Interfaces;

use AppBundle\Entity\Game;


interface StorableGame
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     * @return Game
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getLocal();

    /**
     * @param mixed $local
     * @return Game
     */
    public function setLocal($local);

    /**
     * @return mixed
     */
    public function getVisitor();

    /**
     * @param mixed $visitor
     * @return Game
     */
    public function setVisitor($visitor);

    /**
     * @return mixed
     */
    public function getScoreLocal();

    /**
     * @param mixed $scoreLocal
     * @return Game
     */
    public function setScoreLocal($scoreLocal);

    /**
     * @return mixed
     */
    public function getScoreVisitor();

    /**
     * @param mixed $scoreVisitor
     * @return Game
     */
    public function setScoreVisitor($scoreVisitor);

    /**
     * @return mixed
     */
    public function getWhenPlayed();

    /**
     * @param mixed $whenPlayed
     * @return Game
     */
    public function setWhenPlayed($whenPlayed);
}