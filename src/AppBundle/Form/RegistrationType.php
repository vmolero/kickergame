<?php

namespace AppBundle\Form;

use AppBundle\Entity\Interfaces\RoleHolder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Role;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullName', 'text', ['label' => 'registration.fullname']);
        $builder->add(
            'roles',
            ChoiceType::class,
            [
                'choices' => [
                    Role::create('admin'),
                    Role::create('player'),
                ],
                'choices_as_values' => true,
                'choice_label' => function (RoleHolder $role, $key, $index) {
                    return $role->getType() === Role::ADMIN ? 'Administrator' : 'Player';
                },
                'choice_value' => function ($value) {
                    if ($value instanceof RoleHolder) {
                        return $value->getType();
                    }

                    return null;
                },
                'choice_attr' => function (RoleHolder $role, $key, $index) {
                    return ['class' => 'role_holder'];
                },
                'required' => true,
                'placeholder' => 'Choose your role',
                'expanded' => true,
                'multiple' => true,
            ]
        );
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'tvg_user_registration';
    }
}