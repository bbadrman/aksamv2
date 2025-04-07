<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('commande', Type\TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom'
                ]
            ])
            ->add('datePaiement', Type\DateType::class, [
                'label' => "Date de paiement",

                'widget' => 'single_text',

            ])
            ->add(
                'motif',
                Type\ChoiceType::class,
                [
                    'label' => 'Motif ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        '1er reglement' =>  '1er reglement',
                        '2eme reglement' =>  '2eme reglement',
                        '3eme reglement' =>  '3eme reglement',
                        'Remboursement' =>  'Remboursement',
                        'ContrepartiE' =>  'Contrepartie',

                    ],
                    'expanded' => false,
                    'multiple' => false,

                ]
            )
            ->add('debit', Type\MoneyType::class, [
                'label' => 'Debit ',
                'required' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Tapez en €',
                    'divisor' => 100,

                ],

            ])
            ->add('credit', Type\MoneyType::class, [
                'label' => 'Credit ',
                'required' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Tapez en €',
                    'divisor' => 100,

                ],

            ])
            ->add('comrcl')
            ->add(
                'moyen',
                Type\ChoiceType::class,
                [
                    'label' => 'Moyen de paiement ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'VIREMENT' =>  'VIREMENT',
                        'MANUEL' =>  'MANUEL',
                        'LIEN' =>  'LIEN',
                        'CB' =>  'CB',


                    ],
                    'expanded' => false,
                    'multiple' => false,

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
