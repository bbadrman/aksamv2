<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', Type\TextType::class, [
                'label' => 'Nom ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez le Nom du Client'
                ],
                'required' => true,
            ])
            ->add('prenom', Type\TextType::class, [
                'label' => 'Prénom ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez le Prénom du Client'
                ],
                'required' => true,
            ])
            ->add('raisonSociale', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez le Raison sociale'
                ],
                'required' => false,
            ])
            ->add('dateSouscrpt', Type\DateType::class, [
                'label' => "Date souscription :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => true,
            ])
            ->add('dateEffet', Type\DateType::class, [
                'label' => "Date effet :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => true,
            ])
            ->add(
                'typeContrat',
                Type\ChoiceType::class,
                [
                    'label' => 'Type Contrat ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Particulier' =>  'Particulier',
                        'Professionnel' => 'Professionnel',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add(
                'activite',
                Type\ChoiceType::class,
                [
                    'label' => 'Activites ',
                    'required' => false,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'TPM' =>  'TPM',
                        'VTC' =>   'VTC',
                        'Sociéte' => 'Sociéte',
                        'Décenale' => 'Décenale',
                        'Négociant' =>  'Négociant',
                        'Prof auto' =>  'Prof auto',
                        'Garage' => 'Garage',
                        'AUTO ECOLE' => 'AUTO ECOLE',
                        'BTP' => 'BTP',
                        'BATIMENT' => 'BATIMENT',
                        'DEMENAGEMENT' => 'DEMENAGEMENT',
                        'TAXI' => 'TAXI',
                        'TPPC' => 'TPPC',
                        'TP' => 'TP',
                        'Location de véhicule' => 'Location véhicule',
                        'Convoyage' => 'Convoyage'


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('imatriclt', Type\TextType::class, [
                'label' => 'Immatriculation ',
                'disabled' => false,
                'attr' => [

                    'placeholder' => 'Tapez Immatriculation'
                ],
                'required' => false
            ])
            ->add('partenaire')
            ->add('compagnie')
            ->add(
                'formule',
                Type\ChoiceType::class,
                [
                    'label' => 'Formule ',
                    'required' => false,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'F1' =>  'F1',
                        'F2' =>  'F2',
                        'F3' =>  'F3',
                        'TEMPORAIRE' =>  'TEMPORAIRE',
                        'F1 + VR' =>  'F1 + VR',
                        'F2 sans INCENDIE' =>  'F2 sans INCENDIE',
                        'F2 + BDG' =>  'F2 + BDG',
                        'F3 sans INCENDIE' =>  'F3 sans INCENDIE',
                        'F3 sans BDG' =>  'F3 sans BDG',
                        'F2 sans BDG' =>  'F2 sans BDG',


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add(
                'fraction',
                Type\ChoiceType::class,
                [
                    'label' => 'Fractionnement ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'MENSUEL' => 'MENSUEL',
                        'TRIMESTRIEL' => 'TRIMESTRIEL',
                        'SEMESTRIEL' =>  'SEMESTRIEL',
                        'ANNUEL' =>  'ANNUEL',
                        'SEMAINE' =>  'SEMAINE',
                        '01 JRS' =>  '01 JRS',
                        '02 JRS' =>  '02 JRS',
                        '03 JRS' =>  '03 JRS',
                        '04 JRS' =>  '04 JRS',
                        '05 JRS' =>  '05 JRS',
                        '06 JRS' =>  '06 JRS',
                        '07 JRS' =>  '07 JRS',
                        '08 JRS' =>  '08 JRS',
                        '09 JRS' =>  '09 JRS',
                        '10 JRS' =>  '10 JRS',
                        '11 JRS' =>  '11 JRS',
                        '12 JRS' =>  '12 JRS',
                        '13 JRS' =>  '13 JRS',
                        '14 JRS' =>  '14 JRS',
                        '15 JRS' =>  '15 JRS',
                        '16 JRS' =>  '16 JRS',
                        '17 JRS' =>  '17 JRS',
                        '18 JRS' =>  '18 JRS',
                        '19 JRS' =>  '19 JRS',
                        '20 JRS' =>  '20 JRS',
                        '21 JRS' =>  '21 JRS',
                        '22 JRS' =>  '22 JRS',
                        '23 JRS' =>  '23 JRS',
                        '24 JRS' =>  '24 JRS',
                        '25 JRS' =>  '25 JRS',
                        '26 JRS' =>  '26 JRS',
                        '27 JRS' =>  '27 JRS',
                        '28 JRS' =>  '28 JRS',
                        '29 JRS' =>  '29 JRS',
                        '30 JRS' =>  '30 JRS',
                        '31 JRS' =>  '31 JRS',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('frais', Type\MoneyType::class, [
                'label' => 'Frais  ',

                'disabled' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Tapez en €',
                    'divisor' => 100,

                ],
                'required' => true,

            ])
            ->add(
                'etat',
                Type\ChoiceType::class,
                [
                    'label' => 'Etat Contrat ',
                    'required' => false,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'EN-COURS' =>   'EN-COURS',
                        'SUSPENDU' =>   'SUSPENDU',
                        'ANNULE' =>   'ANNULE',
                        'RESILIE' =>   'RESILIE'


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add(
                'typeConducteur',
                Type\ChoiceType::class,
                [
                    'label' => 'Type Conducteur ',
                    'required' => true,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Désigné' =>  'Désigné',
                        'Multiconducteur' => 'Multiconducteur',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('conducteur', Type\TextType::class, [
                'label' => 'Conducteur ',
                'disabled' => false,
                'required' => false,
                'attr' => [

                    'placeholder' => 'Tapez le Nom du conducteur'
                ],

            ])
            ->add('datePermis', Type\DateType::class, [
                'label' => "Date permis :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => false
            ])
            ->add('status',  Type\ChoiceType::class, [
                'label' => 'status',
                'required' => true,
                'disabled' => true,
                'choices' => [
                    'Valider' => 1,
                    'Rejeter' => 2
                ],

                'expanded' => true,
                'multiple' => false
            ])
            ->add('comment',  Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Votre Commentaire",
                'required' => false,
            ])

            ->add('cotisation', Type\MoneyType::class, [
                'label' => 'Cotisation  ',
                'required' => true,
                'disabled' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Tapez en €',
                    'divisor' => 100,

                ],

            ])
            ->add('acompte', Type\MoneyType::class, [
                'label' => 'Acompte  ',
                'required' => false,
                'disabled' => false,
                'currency' => false,
                'attr' => [
                    'placeholder' => 'Tapez en €',
                    'divisor' => 100,

                ],

            ])

            ->add(
                'jourPrelvm',
                Type\ChoiceType::class,
                [
                    'label' => 'Jour de prélèvement',
                    'required' => false,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie le jour du prélèvement-- ',
                    'choices' => [
                        '01' =>  '01',
                        '02' =>  '02',
                        '03 ' =>  '03 ',
                        '04 ' =>  '04',
                        '05 ' =>  '05',
                        '06 ' =>  '06',
                        '07 ' =>  '07',
                        '08 ' =>  '08',
                        '09 ' =>  '09',
                        '10 ' =>  '10',
                        '11 ' =>  '11',
                        '12 ' =>  '12',
                        '13 ' =>  '13',
                        '14 ' =>  '14',
                        '15 ' =>  '15',
                        '16 ' =>  '16',
                        '17  ' =>  '17',
                        '18  ' =>  '18',
                        '19  ' =>  '19',
                        '20  ' =>  '20',
                        '21  ' =>  '21',
                        '22  ' =>  '22',
                        '23  ' =>  '23',
                        '24  ' =>  '24',
                        '25  ' =>  '25',
                        '26  ' =>  '26',
                        '27  ' =>  '27',
                        '28  ' =>  '28',
                        '29  ' =>  '29',
                        '30  ' =>  '30',
                        '31  ' =>  '31',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('typeProduct', Type\ChoiceType::class, [
                'label' => 'Type Produit',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'AUTOMOBILE' =>  'AUTOMOBILE',
                    'CARAVANE' =>  'CARAVANE',
                    'AUTOCAR' => 'AUTOCAR',
                    'TRACTEUR ROUTIER' =>  'TRACTEUR ROUTIER',
                    'SEMI-REMORQUE' => 'SEMI-REMORQUE',
                    'POIDS LOURS' => 'POIDS LOURS',
                    'CARENE' => 'CARENE',
                    'CAMIONNETTE' => 'CAMIONNETTE',
                    'UTILITAIRE' => 'UTILITAIRE',
                    'MOTO' => 'MOTO',

                ],
                'expanded' => false,
                'multiple' => false,

            ])

            ->add('datePreleveAcompte', Type\DateType::class, [
                'label' => "Date prélèvement acompte :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('dateNaissance', Type\DateType::class, [
                'label' => "Date da naissance :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('dateAchat', Type\DateType::class, [
                'label' => "Date d'achat :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('dateMec', Type\DateType::class, [
                'label' => "Date MEC :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('risqueUsage', Type\ChoiceType::class, [
                'label' => 'Usage',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'vtc' =>  'vtc',
                    'tpm' =>  'tpm',
                    'pro' => 'pro',
                    'privée ' =>  'privée',
                    'pro/privée' => 'pro/privée'

                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('typePermis', Type\ChoiceType::class, [
                'label' => 'Usage',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'A' =>  'A',
                    'B' =>  'B',
                    'C' =>  'C',
                    'D' =>  'D',
                    'E' => 'E',
                    'AM' => 'AM',


                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('suspensionPermis', Type\ChoiceType::class, [
                'label' => 'suspension Permis',

                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('dateSuspension', Type\DateType::class, [
                'label' => "Date Suspension :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('motifSuspension', Type\ChoiceType::class, [
                'label' => 'Usage',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'alcoolemie' =>  'alcoolemie',
                    'stupéfiant' =>  'stupéfiant',
                    'perte point' =>  'perte point',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('annulationPermis', Type\ChoiceType::class, [
                'label' => 'annulation Permis',

                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('dateAnnulation', Type\DateType::class, [
                'label' => "Date Annulation :",
                'disabled' => false,

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd ."
                ],
                'required' => false
            ])
            ->add('motifAnnulation', Type\ChoiceType::class, [
                'label' => 'Usage',
                'required' => true,
                'disabled' => false,
                'choices' => [
                    'alcoolemie' =>  'alcoolemie',
                    'stupéfiant' =>  'stupéfiant',
                    'perte point' =>  'perte point',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('crmActuel',  Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "crm Actuel",
                'required' => false,
            ])
            ->add('crmRetenu', Type\MoneyType::class, [
                'label' => 'crm Retenu',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('garanties',  Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "voir devis n°",
                'required' => false,
            ])
            ->add('facilite', Type\ChoiceType::class, [
                'label' => 'facilite',

                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('NmbrReglement', Type\ChoiceType::class, [
                'label' => 'Nombre de règlements',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])
            ->add('NmbrAssure', Type\ChoiceType::class, [
                'label' => 'Nombre des assureurs',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])

            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'id',
            ])
            ->add('regelement', CollectionType::class, [
                'entry_type' => RegelementType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true, // Important
                'label' => false,
            ])
            ->add('antcdAssure', CollectionType::class, [
                'entry_type' => AntecedentType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,  // Important pour la génération dynamique
                'label' => false,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
