<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use AppBundle\Form\NewGameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        // !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && $this->redirect();
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
     * @Route("/admin/players", name="showPlayers")
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
     * @Route("/admin/teams", name="showTeams")
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/games", name="showGames")
     */
    public function showGamesAction(Request $request)
    {
        $games = ($this->getDoctrine()
            ->getRepository('AppBundle:Game')->findAll());

        // ->findBy(['role' => Role::PLAYER]));
        return $this->render(
            'admin/games.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'games' => $games,
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/games/new", name="addTeam")
     */
    public function showFormNewGameAction(Request $request)
    {
        $form = $this->createForm(NewGameType::class, ['players' => $this->getDoctrine()
            ->getRepository('AppBundle:User')->findByRole(Role::PLAYER)]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getRepository('AppBundle:Game')->saveUsingFormData($form->getData());
        }
        return $this->render(
            'teams/new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

}