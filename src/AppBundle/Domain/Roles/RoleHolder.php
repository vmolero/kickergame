<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/5/17
 * Time: 1:57 PM
 */

namespace AppBundle\Domain\Roles;


interface RoleHolder
{
    public function getRole();
    public function getName();
    public function getCode();
}