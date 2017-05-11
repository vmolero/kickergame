<?php

namespace AppBundle\Domain;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Interfaces\RoleHolder;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class RoleUser
 * @package AppBundle\Domain
 */
abstract class RoleUser implements KickerUserInterface, RoleInterface
{
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var RoleHolder
     */
    private $role;

    /**
     * RoleUser constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->user->getId();
    }

    /**
     * @return mixed
     */
    public function setId()
    {
        return $this->user->setId();
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->user->getFullname();
    }

    /**
     * @param $fullName
     * @return mixed
     */
    public function setFullName($fullName)
    {
        return $this->user->setFullname($fullName);
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->user->getRoles();
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->user->getSalt();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role->getRole();
    }

    /**
     * @param $role
     */
    protected function setRole(RoleInterface $role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRoleShortString()
    {
        return strtolower(substr($this->role, 5));
    }

    /**
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username)
    {
        return $this->user->setUsername($username);
    }

    /**
     * @return string
     */
    public function getUsernameCanonical()
    {
        return $this->user->getUsernameCanonical();
    }

    /**
     * @param string $usernameCanonical
     * @return UserInterface
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        return $this->user->setUsernameCanonical($usernameCanonical);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->user->getEmail();
    }

    /**
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        return $this->user->setEmail($email);
    }

    /**
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->user->getEmailCanonical();
    }

    /**
     * @param string $emailCanonical
     * @return UserInterface
     */
    public function setEmailCanonical($emailCanonical)
    {
        return $this->user->setEmailCanonical($emailCanonical);
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->user->getPlainPassword();
    }

    /**
     * @param string $password
     * @return UserInterface
     */
    public function setPlainPassword($password)
    {
        return $this->user->setPlainPassword($password);
    }

    /**
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        return $this->user->setPassword($password);
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->user->isSuperAdmin();
    }

    /**
     * @param UserInterface|null $user
     * @return bool
     */
    public function isUser(UserInterface $user = null)
    {
        return $this->user->isUser($user);
    }

    /**
     * @param bool $boolean
     * @return UserInterface
     */
    public function setEnabled($boolean)
    {
        return $this->user->setEnabled($boolean);
    }

    /**
     * @param bool $boolean
     * @return UserInterface
     */
    public function setLocked($boolean)
    {
        return $this->user->setLocked($boolean);
    }

    /**
     * @param bool $boolean
     * @return UserInterface
     */
    public function setSuperAdmin($boolean)
    {
        return $this->user->setSuperAdmin($boolean);
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->user->getConfirmationToken();
    }

    /**
     * @param string $confirmationToken
     * @return UserInterface
     */
    public function setConfirmationToken($confirmationToken)
    {
        return $this->user->setConfirmationToken($confirmationToken);
    }

    /**
     * @param \DateTime|null $date
     * @return UserInterface
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        return $this->user->setPasswordRequestedAt($date);
    }

    /**
     * @param int $ttl
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->user->isPasswordRequestNonExpired($ttl);
    }

    /**
     * @param \DateTime $time
     * @return UserInterface
     */
    public function setLastLogin(\DateTime $time)
    {
        return $this->user->setLastLogin($time);
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->user->hasRole($role);
    }

    /**
     * @param array $roles
     * @return UserInterface
     */
    public function setRoles(array $roles)
    {
        return $this->user->setRoles($roles);
    }

    /**
     * @param string $role
     * @return UserInterface
     */
    public function addRole($role)
    {
        return $this->user->addRole($role);
    }

    /**
     * @param string $role
     * @return UserInterface
     */
    public function removeRole($role)
    {
        return $this->user->removeRole($role);
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return $this->user->isAccountNonExpired();
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return $this->user->isAccountNonLocked();
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return $this->user->isCredentialsNonExpired();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->user->isEnabled();
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return $this->user->serialize();
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        return $this->user->unserialize($serialized);
    }

    /**
     * @return UserInterface
     */
    public function getEntity()
    {
        return $this->user->getEntity();
    }
}