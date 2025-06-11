<?php

namespace App\Form;

use App\Entity\User;
use App\Search\SearchTransaction;
use App\Search\SearchUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une date de début'
                    ]),
                    new LessThanOrEqual([
                        'propertyPath' => 'parent.all[dd].data',
                        'message' => 'La date de début doit être antérieure ou égale à la date de fin'
                    ])
                ]
            ])

            ->add('dd', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une date de fin'
                    ]),
                    new GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[d].data',
                        'message' => 'La date de fin doit être postérieure ou égale à la date de début'
                    ])
                ]
            ])
            ->add('comrcl', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Sélectionnez un Commercial',
                'choice_label' => 'username',
                'required' => false,
                'label' => 'Commercial'
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
