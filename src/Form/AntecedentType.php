<?php

namespace App\Form;

use App\Entity\AntcdentAssurance;
use App\Entity\Contrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class AntecedentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('assureur', Type\TextType::class, [
                'label' => 'Assureur ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez le Nom d Assureur'
                ],
                'required' => true,
            ])
            ->add('nbrMois', Type\NumberType::class, ['label' => 'Nombre de mois'])
            ->add(
                'etatContrat',
                Type\ChoiceType::class,
                [
                    'label' => 'Etat contrat ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'encours' =>  'encours',
                        'suspendu' => 'suspendu',
                        'Resiliés' => [
                            'non paiement' => 'non paiement',
                            'écheance' => 'écheance',
                            'sinistres' => 'résilié',
                            'fausse déclaration' => 'fausse déclaration',
                        ]

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('sinistres', Type\ChoiceType::class, [
                'label' => 'Sinistres',

                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('nbrSinistre', Type\NumberType::class, ['label' => 'Nombre de sinistres'])
            ->add(
                'typeSinistre',
                Type\ChoiceType::class,
                [
                    'label' => 'Type sinistre ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Materiel' =>  'Materiel',
                        'Corporel' => 'Corporel',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('responsable', Type\ChoiceType::class, [
                'label' => 'Responsabilité',

                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add(
                'pourcentResp',
                Type\ChoiceType::class,
                [
                    'label' => '% responsabilite ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        '0%' =>  '0',
                        '50%' => '50',
                        '100%' => '50',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('brisGlace', Type\ChoiceType::class, [
                'label' => 'Bris de glace',

                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('volIncendie', Type\ChoiceType::class, [
                'label' => 'Vol et incendie',

                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('crmAct', Type\TextType::class, [
                'label' => 'CRM Actuel ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez le CRM Actuel'
                ],
                'required' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AntcdentAssurance::class,
        ]);
    }
}
