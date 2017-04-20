<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class GameController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @Route("/admin/games/new/", name="newGameByAdmin")
     * @Route("/players/games/new/", name="newGameByPlayer")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFormNewGameAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createForm(
            GameType::class,
            new Game(),
            [
                'players' => $this->getDoctrine()
                    ->getRepository('AppBundle:User')->findByRole(Role::PLAYER),
            ]
        )->add('save', SubmitType::class, array('label' => 'Create Game'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Game $game */
            $game = $form->getData();
            $game->setCreatedBy($user);
            if ($game->hasConflicts()) {
                $this->get('session')->getFlashBag()->set('registrationMessage', 'Game has team conflicts');
            }
            else if ($user->hasRole(Role::PLAYER) && !$game->hasPlayer($user)) {
                $this->get('session')->getFlashBag()->set('registrationMessage', 'You must be part of the game');
            } else {
                $this->getDoctrine()->getRepository('AppBundle:Team')->useExistingTeamsFor($game);
                $game->setStatus(Game::OPEN);
                $em->persist($game);
                $em->flush();
                $this->get('session')->getFlashBag()->set('registrationMessage', 'Game saved');
            }
        }

        return $this->render(
            'games/new.html.twig',
            array(
                'form' => $form->createView(),
                'baseUrl' => $request->getBaseUrl(),
                'roleUrl' => in_array(ROle::ADMIN, $user->getRoles()) ? 'admin' : 'players',
            )
        );
    }

    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/games/", name="playerGames")
     * @Route("/players/{id}/games/", name="specificPlayerGames")
     */
    public function showGamesAction(Request $request, $id = null)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $role = in_array(Role::ADMIN, $user->getRoles()) ? 'admin' : 'players';
        $gameRepo = $this->getDoctrine()
            ->getRepository('AppBundle:Game');
        $games = $id ? $gameRepo->findAllGamesByPlayer($id) : $gameRepo->findAll();
        $canConfirm = [];
        foreach ($games as $aGame) {
            $aGame->canBeConfirmedBy($user) && ($canConfirm[$aGame->getId()] = 1);
        }

        return $this->render(
            'games/'.$role.'.html.twig',
            [
                'baseUrl' => $request->getBaseUrl(),
                'roleUrl' => $role,
                'games' => $games,
                'referrer' => $request->getRequestUri(),
                'canConfirm' => $canConfirm,
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_PLAYER')")
     * @Route("/game/{id}/score/confirm", name="confirmGame")
     * @param Request $request
     * @param integer $id
     * @return ResponseRedirect
     */
    public function confirmGameAction(Request $request, $id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $from = $request->query->get('from');
        /** @var Game $game */
        $game = $this->getDoctrine()
            ->getRepository('AppBundle:Game')->find($id);
        $game->setConfirmedBy($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        return new RedirectResponse(urldecode($from));
    }
}