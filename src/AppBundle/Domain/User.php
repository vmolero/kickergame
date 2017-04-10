<?php

namespace AppBundle\Domain;


use AppBundle\Domain\Roles\RoleHolder;

class User extends \AppBundle\Entity\User
{
    public function addRole($role)
    {
        is_string($role) && ($role = Role::create($role));
        return $this->addToRoleArray($role);
    }

    private function addToRoleArray(RoleHolder $role)
    {
        $roleCode = strtoupper($role->getCode());
        if ($roleCode === Role::PLAYER) {
            return $this;
        }
        if (!in_array($roleCode, $this->roles, true) &&
            $this->isValidRole($roleCode)) {
            $this->roles[] = $roleCode;
        }

        return $this;
    }

    private function isValidRole($roleCode) {
        return is_string($roleCode) &&
               in_array($roleCode, Role::getValidRoles()) &&
               preg_match('/^ROLE\_/', $roleCode) === 1;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        // we need to make sure to have at least one role
        $roles[] = Role::PLAYER;

        return array_unique($roles);
    }
}