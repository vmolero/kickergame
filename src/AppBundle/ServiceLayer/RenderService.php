<?php

namespace AppBundle\ServiceLayer;


use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class RenderService
 * @package AppBundle\ServiceLayer
 */
class RenderService
{
    /**
     * @var EngineInterface
     */
    private $renderEngine;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string|array
     */
    private $template;

    /**
     * RenderService constructor.
     * @param EngineInterface $renderEngine
     * @param SessionInterface $session
     */
    public function __construct(EngineInterface $renderEngine, SessionInterface $session)
    {
        $this->renderEngine = $renderEngine;
        $this->session = $session;
    }

    /**
     * @param string|array $template
     * @return RenderService
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param RoleHandler $handler
     * @return Response
     */
    public function buildResponse(RoleHandler $handler)
    {
        $this->fillFlashBag($handler->getMessages());

        return $this->render(
            $this->getTemplate($handler),
            $handler->getParameters()
        );
    }

    /**
     * @param array $messages
     */
    public function fillFlashBag(array $messages)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $this->session->getFlashBag();
        foreach ($messages as $index => $message) {
            $flashBag->set($index, $message);
        }
    }

    /**
     * @param string $template
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    private function render($template, array $parameters = array(), Response $response = null)
    {
        if (!$this->renderEngine) {
            throw new \LogicException(
                'You can not use the "render" method if the Templating Engine is not available.'
            );
        }
        if ($response === null) {
            $response = new Response();
        }

        return $response->setContent($this->renderEngine->render($template, $parameters));
    }

    /**
     * @param RoleHandler $handler
     * @return array|mixed|string
     */
    private function getTemplate(RoleHandler $handler)
    {
        if (is_string($this->template)) {
            return $this->template;
        }
        $roleParameter = $handler->getRoleTemplateParameter();
        if (isset($this->template[$roleParameter])) {
            return $this->template[$roleParameter];
        }
        throw new LogicException('You must provide a template for the role');
    }
}