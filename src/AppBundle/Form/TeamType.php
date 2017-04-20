<?php

namespace AppBundle\Form;

use AppBundle\Entity\Team;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $players = $options['players'];
        $builder->add(
            'player1',
            ChoiceType::class,
            [
                'choices' => $players,
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
                'label' => 'Defender',
            ]
        )
            ->add(
                'player2',
                ChoiceType::class,
                [
                    'choices' => $players,
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
                    'label' => 'Attacker',
                ]
            );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'team_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('players')->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}