<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                Type\ChoiceType::class,
                [
                    'label' => 'Type de paiement ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'FRAIS' =>   'frais',
                        'COTISATION' =>   'cotisation',
                        'CONTRE PARTIE' =>   'contrePartie',


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('frais', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Frais",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('cotisation', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Cotisation",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('contrePartie', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Contre Partie",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])

            ->add('NmbrReglement', Type\ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
                'label' => 'Nombre de paiements',
                'required' => true,
            ])

            ->add('montant', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "montant",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('transaction', Type\TextType::class, ['label' => 'Transaction N°', 'required' => false,])
            ->add('datePayment', Type\DateType::class, [
                'widget' => 'single_text',


            ])
            ->add('montant1', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "montant",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('transaction1', Type\TextType::class, ['label' => 'Transaction1 N°', 'required' => false,])
            ->add('datePayment1', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => true,


            ])
            ->add('montant2', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "montant",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('transaction2', Type\TextType::class, ['label' => 'Transaction2 N°', 'required' => false,])
            ->add('datePayment2', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => true,


            ])
            ->add('montant3', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "montant",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('transaction3', Type\TextType::class, ['label' => 'Transaction3 N°', 'required' => false,])
            ->add('datePayment3', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => true,


            ])


            ->add('creatAt', null, [
                'widget' => 'single_text',
            ])


            ->add(
                'moyen',
                Type\ChoiceType::class,
                [
                    'label' => 'Moyen Paiement ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'MANUEL' =>   'MANUEL',
                        'LIEN' =>   'LIEN',
                        'VIREMENT' =>   'VIREMENT',


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            // ->add('contrat', EntityType::class, [
            //     'class' => Contrat::class,
            //     'disabled' => true,
            //     'choice_label' => 'nom',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
