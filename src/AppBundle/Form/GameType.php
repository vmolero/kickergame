<?php

namespace AppBundle\Form;

use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $players = $options['players'];

        $builder
            ->add(
                'local',
                TeamType::class,
                [
                    'players' => array_map(function (KickerUserInterface $player) { return $player->getEntity(); }, $players),
                    'label' => 'Local team',
                ]
            )
            ->add(
                'visitor',
                TeamType::class,
                [
                    'players' => array_map(function (KickerUserInterface $player) { return $player->getEntity(); }, $players),
                    'label' => 'Visitor team',
                ]
            )
            ->add(
                'scoreLocal',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'scoreVisitor',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'whenPlayed',
                DateTimeType::class
            );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'game_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('players')->setDefaults(
            [
                'data_class' => Game::class,
            ]
        );
    }

}