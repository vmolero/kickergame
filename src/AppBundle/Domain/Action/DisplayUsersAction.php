<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Role;
use AppBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DisplayUsersAction
 * @package AppBundle\Domain\Action
 */
class DisplayUsersAction extends Action
{
    /**
     * @var UserRepository|null
     */
    private $userRepo = null;

    /**
     * DisplayUsersAction constructor.
     * @param Request $request
     * @param UserRepository $repo
     */
    public function __construct(Request $request, UserRepository $repo)
    {
        parent::__construct($request);
        $this->userRepo = $repo;
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitPlayer(KickerUserInterface $user)
    {
        return new ActionResponse(['players' => $this->userRepo->findByRole(Role::PLAYER)]);
    }

    /**
     * @param KickerUserInterface $user
     * @return ActionResponse
     */
    public function visitAdmin(KickerUserInterface $user)
    {
        return new ActionResponse(['users' => $this->userRepo->findAll()]);
    }
}