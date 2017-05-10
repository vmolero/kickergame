<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\RoleUser;
use AppBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class DisplayUsersAction extends Action
{
    private $userRepo = null;

    /**
     * DisplayUsersAction constructor.
     */
    public function __construct(Request $request, UserRepository $repo)
    {
        parent::__construct($request);
        $this->userRepo = $repo;
    }

    public function visitPlayer(UserInterface $user)
    {
        return new ActionResponse(['players' => $this->userRepo->findByRole(RoleUser::PLAYER)]);
    }

    public function visitAdmin(UserInterface $user)
    {
        return new ActionResponse(['users' => $this->userRepo->findAll()]);
    }
}