<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/12/17
 * Time: 10:35 AM
 */

namespace AppBundle\Entity\Interfaces;


/**
 * @ORM\Entity
 * @ORM\Table(name="tvg_role")
 */
interface RoleHolder
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param mixed $type
     */
    public function setType($type);
}