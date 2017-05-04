<?php

namespace AppBundle\RoleHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class RoleHandler
{
    private $renderEngine = null;
    private $session = null;
    private $translator = null;

    /**
     * RoleHandler constructor.
     * @param EngineInterface $renderEngine
     * @param SessionInterface $session
     */
    public function __construct(
        EngineInterface $renderEngine,
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->renderEngine = $renderEngine;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return null|EngineInterface
     */
    public function getTemplating()
    {
        return $this->renderEngine;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    public function handle($name, Request $request, array $data = [])
    {
        try {
            $outcome = $this->{$name.'Action'}($request, $data);
        } catch (\Exception $m) {
            throw new \LogicException($m->getMessage());
        }

        return $outcome;
    }

    /**
     * @param $view
     * @param array $parameters
     * @param Response|null $response
     * @return mixed
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if ($this->renderEngine) {
            return $this->renderEngine->renderResponse($view, $parameters, $response);
        }
        throw new \LogicException(
            'You can not use the "render" method if the Templating Engine is not available.'
        );
    }
    
    protected function invalidPlayersAction(array $data)
    {
        return !(array_key_exists('userRepository', $data));
    }
    
    protected function invalidGamesAction(array $data)
    {
        return !(array_key_exists('id', $data) &&
            array_key_exists('gameRepository', $data) &&
            array_key_exists('user', $data));
    }
    
    protected function invalidConfirmGameAction(array $data)
    {
        return $this->invalidGamesAction($data) ||
            !array_key_exists('from', $data);
    }
    
    protected function invalidNewGameAction(array $data)
    {
        return
            !(array_key_exists('userRepository', $data) &&
                array_key_exists('teamRepository', $data) &&
                array_key_exists('gameRepository', $data) &&
                array_key_exists('user', $data) &&
                array_key_exists('formFactory', $data));
    }
}