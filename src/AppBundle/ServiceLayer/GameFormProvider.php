<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 11/05/17
 * Time: 12:10
 */

namespace AppBundle\ServiceLayer;


use AppBundle\Entity\Game;
use AppBundle\Entity\Role;
use AppBundle\Form\GameType;
use AppBundle\Repository\TeamRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameFormProvider
 * @package AppBundle\ServiceLayer
 */
class GameFormProvider
{
    /**
     * @var Form
     */
    private $form = null;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UserRepository
     */
    private $userRepo;
    /**
     * @var TeamRepository
     */
    private $teamRepo;


    /**
     * GameFormProvider constructor.
     * @param FormFactoryInterface $formFactory
     * @param ObjectRepository $userRepo
     * @param ObjectRepository $teamRepo
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ObjectRepository $userRepo,
        ObjectRepository $teamRepo
    ) {
        $this->formFactory = $formFactory;
        $this->userRepo = $userRepo;
        $this->teamRepo = $teamRepo;
    }

    /**
     * @param string $submitLabel
     * @return $this
     */
    public function createGameForm($submitLabel)
    {
        $this->form = $this->formFactory->create(
            GameType::class,
            new Game(),
            [
                'players' => $this->userRepo->findByRole(Role::PLAYER),
            ]
        )->add('save', SubmitType::class, ['label' => $submitLabel]);

        return $this;
    }

    /**
     * @param Request $request
     * @return Game|null
     */
    public function buildGameEntity(Request $request)
    {
        $this->form->handleRequest($request);
        if (!$this->form->isSubmitted() || !$this->form->isValid()) {
            return null;
        }
        $game = $this->form->getData();
        $game = $this->teamRepo->useExistingTeamsFor($game);

        return $game;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createView()
    {
        if ($this->form instanceof FormInterface) {
            return $this->form->createView();
        }
        throw new Exception('Missing form');
    }
}