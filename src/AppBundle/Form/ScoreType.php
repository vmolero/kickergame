<?php

namespace AppBundle\Form;

use AppBundle\Entity\Team;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ScoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'scoreLocal',
            IntegerType::class,
            [
                'required' => true,
                'label' => 'Local score'
            ]
        )
            ->add(
                'scoreVisitor',
                IntegerType::class,
                [
                    'required' => true,
                    'label' => 'Visitor score'
                ]
            );
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'score_form';
    }
}