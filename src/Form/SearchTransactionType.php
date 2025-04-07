<?php

namespace App\Form;

use App\Search\SearchTransaction;
use App\Search\SearchUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', Type\TextType::class, [
                'label' => "Nom :",
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom..."
                ],
            ])
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
            ->add('d', Type\DateType::class, [
                'label' => "Date Du :",

                'widget' => 'single_text',


                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => false
            ])

            ->add('dd', Type\DateType::class, [
                'label' => "Ou :",

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => false
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
                        'Contrepartie' =>  'Contrepartie',

                    ],
                    'expanded' => false,
                    'multiple' => false,

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchTransaction::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
