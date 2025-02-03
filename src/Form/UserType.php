<?php

namespace App\Form;

use App\Entity\Permission;
use App\Entity\Product;
use App\Entity\Team;
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
                    'label' => 'Confirmez mot de passe',
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
            ->add('embuchAt', Type\DateType::class, [
                'label' => "Date d'embauche",

                'widget' => 'single_text',

            ])
            ->add('remuneration')
            ->add('fonction', ChoiceType::class, [
                'label' => 'Type Produit',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'Directeur' =>  'directeur',
                    'COMERCIAL' =>  'comercial',
                    'CHEF EQUIPE' => 'chef',
                    'MANAGER' =>  'manager',
                    'SUPORT' => 'suport',
                    'DIVELOPPER' => 'divlopper',
                    'TECH' => 'tech',
                    'AUTRE' => 'autre',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('status', Type\ChoiceType::class, [
                'label' => 'Status',

                'choices' => [
                    'Active' => 1,
                    'Desactive' => 0
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('permissions', EntityType::class, [
                'class' => Permission::class,
                'choice_label' => function (Permission $permission) {
                    return ucfirst(strtolower(str_replace('ROLE_', '', $permission->getNom())));
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('products')
            ->add('teams')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
