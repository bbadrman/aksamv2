<?php

namespace App\Form;

use App\Entity\Permission;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as Type;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Admin' => 'ROLE_ADMIN',
            //         'User' => 'ROLE_USER',
            //     ],
            //     'multiple' => true, // Permet de sélectionner plusieurs rôles
            //     'expanded' => true, // Affiche des cases à cocher au lieu d'une liste déroulante
            // ])
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'required' => true,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ],
                    'error_bubbling' => true
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmez votre mot de passe'
                    ],
                    'error_bubbling' => true,
                    'constraints' => new Assert\NotBlank(
                        [
                            'message' => 'La confirmation du mot de passe est obligatoire'
                        ]
                    )
                ]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('embuchAt', null, [
                'widget' => 'single_text',
            ])
            ->add('remuneration')
            ->add('fonction')
            ->add('status')
            ->add('permissions', EntityType::class, [
                'class' => Permission::class,
                //getName
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
