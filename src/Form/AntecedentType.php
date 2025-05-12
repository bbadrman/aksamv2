<?php

namespace App\Form;

use App\Entity\AntcdentAssurance;
use App\Entity\Contrat;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
                'placeholder' => '--Merci de selectie-- ',

                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],

                'expanded' => false,
                'multiple' => false,

            ])
            ->add('nbrSinistre', Type\ChoiceType::class, [
                'label' => 'Nombre sinistre',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])

            ->add('material', Type\ChoiceType::class, [
                'label' => 'Sinistre Materiel',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('corporel', Type\ChoiceType::class, [
                'label' => 'Sinistre Corporel',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('zeresponsable', Type\ChoiceType::class, [
                'label' => '0% responsable',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('cinqResponsable', Type\ChoiceType::class, [
                'label' => '50% responsable',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('centResponsable', Type\ChoiceType::class, [
                'label' => '100% responsable',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('brisGlace', Type\ChoiceType::class, [
                'label' => 'Bris de glace',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('volIncendie', Type\ChoiceType::class, [
                'label' => 'Vol et incendie',
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'oui' => 'oui',
                    'non' => 'non'
                ],
                'expanded' => false,
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
