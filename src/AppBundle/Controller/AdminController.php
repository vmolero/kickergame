<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Users\Player;
use AppBundle\Entity\Team;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->getRepository('AppBundle:User')->findAll());

        // ->findBy(['role' => Role::PLAYER]));
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
        $r = ($this->getDoctrine()
            ->getRepository('AppBundle:Game')->findAll());

        // ->findBy(['role' => Role::PLAYER]));
        return $this->render(
            'admin/games.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'registerUrl' => '/register',
                'games' => $r,
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/teams/new", name="addTeam")
     */
    public function showFormNewTeamAction()
    {
        $team = new Team();
        $player1 = new Player();
        $player1->setFullName('Víctor')->setUsername('vmolero');
        $player2 = new Player();
        $player2->setFullName('Pepe')->setUsername('pepe');
        $player3 = new Player();
        $player3->setFullName('Alberto')->setUsername('albelcro');
        $players = [$player2, $player1, $player3];
        $form = $this->createFormBuilder($team)
            // ->add('name', TextType::class)
            ->add(
                'player1',
                ChoiceType::class,
                [
                    'choices' => $players,
                    'choices_as_values' => true,
                    'choice_label' => function (UserInterface $player, $key, $index) {
                        echo $player->getFullName();

                        return $player->getFullName();
                    },
                    'choice_value' => function ($value) {
                        if ($value instanceof UserInterface) {
                            return $value->getId();
                        }

                        return null;
                    },
                    'placeholder' => 'Choose a player',
                ]
            )
            // ->add('player2', ChoiceType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Team'))
            ->getForm();

        return $this->render(
            'teams/new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

}