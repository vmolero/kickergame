<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/", name="adminHomepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render(
            'admin/index.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'playersUrl' => '/admin/players',
                'teamsUrl' => '/admin/teams',
                'gamesUrl' => '/admin/games',
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/players/", name="showPlayers")
     */
    public function showPlayersAction(Request $request)
    {
        $r = ($this->getDoctrine()
            ->getRepository('AppBundle:User')->findByRole(Role::PLAYER));

        return $this->render(
            'admin/players.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'players' => $r,
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/teams/", name="showTeams")
     */
    public function showTeamsAction(Request $request)
    {
        $r = ($this->getDoctrine()
            ->getRepository('AppBundle:Team')->findAll());

        // ->findBy(['role' => Role::PLAYER]));
        return $this->render(
            'admin/teams.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'newTeamUrl' => '/admin/teams/new',
                'teams' => $r,
            ]
        );
    }

}