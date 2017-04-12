<?php

namespace AppBundle\Form;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class NewGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // var_dump($options);
        //die;
        $builder->add(
                'player1',
                ChoiceType::class,
                [
                    'choices' => $options['data']['players'],
                    'choices_as_values' => true,
                    'choice_label' => function (UserInterface $player, $key, $index) {
                        return $player->getFullName();
                    },
                    'choice_value' => function ($value) {
                        if ($value instanceof UserInterface) {
                            return $value->getId();
                        }
                        return null;
                    },
                    'placeholder' => 'Choose a player',
                    'label' => 'Local player 1'
                ]
            )
            ->add(
                'player2',
                ChoiceType::class,
                [
                    'choices' => $options['data']['players'],
                    'choices_as_values' => true,
                    'choice_label' => function (UserInterface $player, $key, $index) {
                        return $player->getFullName();
                    },
                    'choice_value' => function ($value) {
                        if ($value instanceof UserInterface) {
                            return $value->getId();
                        }
                        return null;
                    },
                    'placeholder' => 'Choose a player',
                    'label' => 'Local player 2'
                ]
            )
            ->add(
                'player3',
                ChoiceType::class,
                [
                    'choices' => $options['data']['players'],
                    'choices_as_values' => true,
                    'choice_label' => function (UserInterface $player, $key, $index) {
                        return $player->getFullName();
                    },
                    'choice_value' => function ($value) {
                        if ($value instanceof UserInterface) {
                            return $value->getId();
                        }
                        return null;
                    },
                    'placeholder' => 'Choose a player',
                    'label' => 'Visitor player 1'
                ]
            )
            ->add(
                'player4',
                ChoiceType::class,
                [
                    'choices' => $options['data']['players'],
                    'choices_as_values' => true,
                    'choice_label' => function (UserInterface $player, $key, $index) {
                        return $player->getFullName();
                    },
                    'choice_value' => function ($value) {
                        if ($value instanceof UserInterface) {
                            return $value->getId();
                        }
                        return null;
                    },
                    'placeholder' => 'Choose a player',
                    'label' => 'Visitor player 2'
                ]
            )
            ->add('when',
                  DateTimeType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Game'));
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'new_game_form';
    }
}