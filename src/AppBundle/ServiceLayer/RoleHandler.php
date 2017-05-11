<?php

namespace AppBundle\ServiceLayer;

use AppBundle\Domain\Action\Action;
use AppBundle\Domain\Admin;
use AppBundle\Domain\Player;
use AppBundle\Entity\Role;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class RoleHandler
 * @package AppBundle\ServiceLayer
 */
class RoleHandler implements RoleInterface
{
    /**
     * @var Admin|Player
     */
    private $user;
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $redirectTo;
    /**
     * @var ActionResponse
     */
    private $actionResponse;

    /**
     * RoleHandler constructor.
     * @param AuthorizationCheckerInterface $checker
     * @param TokenStorageInterface $storage
     */
    public function __construct(AuthorizationCheckerInterface $checker, TokenStorageInterface $storage)
    {
        $user = new Player($storage->getToken()->getUser());
        $checker->isGranted(Role::ADMIN) && ($user = new Admin($user));
        $this->user = $user;
    }

    /**
     * @param Action $action
     * @param array $parameters
     * @return $this
     */
    public function handle(Action $action, array $parameters = [])
    {
        $this->actionResponse = $action->visit($this->getUser());

        if (isset($parameters[$this->getRoleTemplateParameter()])) {
            $this->setTemplateFromConfig($parameters);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getRoleTemplateParameter()
    {
        return strtolower(substr($this->getRole(), 5));
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->user->getRole();
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setTemplateFromConfig(array $parameters)
    {
        if (!is_null($parameters) &&
            !isset($parameters[$this->getRoleTemplateParameter()])
        ) {
            throw new LogicException('You must provide a template for the role');
        }
        $this->setTemplate($parameters[$this->getRoleTemplateParameter()]);

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->actionResponse->getMessages();
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $templatePath
     * @return $this
     */
    public function setTemplate($templatePath)
    {
        $this->template = $templatePath;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->actionResponse->getParameters();
    }

    /**
     * @return mixed
     */
    public function getRedirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * @param mixed $redirectTo
     * @return RoleHandler
     */
    protected function setRedirectTo($redirectTo)
    {
        $this->redirectTo = $redirectTo;

        return $this;
    }

    /**
     * @param $view
     * @param array $parameters
     * @param array $messages
     * @return array
     */
    protected function setResult($view, array $parameters = [], array $messages = [])
    {
        $this->setTemplate($view);
        $this->setParameters($parameters);
        $this->setMessages($messages);

        return $this;
    }

    /**
     * @param $routeName
     * @param array $messages
     * @return array
     */
    protected function setRedirect($routeName, array $messages = [])
    {
        $this->setRedirectTo($routeName);
        $this->setMessages($messages);

        return $this;
    }
}