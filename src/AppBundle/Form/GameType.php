<?php

namespace AppBundle\Form;

use AppBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                    'players' => $players,
                    'label' => 'Local player',
                ]
            )
            ->add(
                'visitor',
                TeamType::class,
                [
                    'players' => $players,
                    'label' => 'Visitor player',
                ]
            )
            ->add(
                'scoreLocal',
                NumberType::class,
                ['required' => false]
            )
            ->add(
                'scoreVisitor',
                NumberType::class,
                ['required' => false]
            )
            ->add(
                'whenPlayed',
                DateTimeType::class
            )
            ->add('save', SubmitType::class, array('label' => 'Create Game'));
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
        $resolver->setRequired('players')->setDefaults([
            'data_class' => Game::class,
        ]);
    }

}