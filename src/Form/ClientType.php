<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', Type\TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom du client'
                ]
            ])
            ->add('prenom', Type\TextType::class, [
                'label' => 'Prénom ',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir le prénom du client'
                ]
            ])
            ->add('phone', Type\TextType::class, [
                'label' => 'Téléphone 2',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir le numéro de téléphone'
                ]
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => "Merci de saisir l'adresse email"
                ]
            ])
            ->add('raisonSociale', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])
            ->add('adress', Type\TextareaType::class, [
                'label' => 'Addresse complét *',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Address compltét du client',
                ]
            ])

            ->add('status', Type\ChoiceType::class, [
                'label' => 'status',
                'required' => true,
                'choices' => [
                    'Valider' => 1,
                    'Rejeter' => 2,
                    'Annulé' => 3

                ],

                'expanded' => true,
                'multiple' => false
            ])
            ->add('comment', Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Remarque",
                'required' => false,
            ])

            ->add('forceJuridique', Type\ChoiceType::class, [
                'label' => 'formes juridiques',
                'required' => true,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'SARL' =>  '1',
                    'EI' => '2',
                    'EURL' => '3',
                    'SA' => '4',
                    'SAS' => '5',
                    'SASU' => '6',
                    'SNC' => '7',
                    'SCOP' => '8',
                    'Association' => '9',

                ],
                'expanded' => false,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
