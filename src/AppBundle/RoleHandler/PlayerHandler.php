<?php

namespace AppBundle\RoleHandler;


use AppBundle\Entity\Game;
use Symfony\Component\HttpFoundation\Request;

class PlayerHandler extends RoleHandler
{
    public function dashboardAction(Request $request, array $data = [])
    {
        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

    public function playersAction(Request $request, array $data = [])
    {
        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/games',
            ]
        );
    }

    public function gamesAction(Request $request, array $data = [])
    {
        return $this->render(
            'players/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/players/games',
            ]
        );
    }

    public function confirmGameAction(Request $request, array $data = [])
    {
        if ($this->invalidConfirmGameAction($data))
        {
            throw new \LogicException('Missing data keys');
        }
        /** @var Game $game */
        $game = $this->getDoctrine()
            ->getRepository(Game::REPOSITORY)->find($data['id']);
        if ($game->isConfirmed()) {
            $this->getSession()->getFlashBag()->set('confirmation.already', 'Game already confirmed');
        } else {
            $game->setConfirmedBy($data['user'])->setStatus(Game::CLOSED);
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();
        }
        return new RedirectResponse(urldecode($data['from']));
    }

    private function invalidConfirmGameAction(array $data)
    {
        return array_key_exists('id', $data) &&
            array_key_exists('from', $data) &&
            array_key_exists('user', $data);
    }

}