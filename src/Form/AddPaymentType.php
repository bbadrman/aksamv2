<?php

namespace App\Form;

use App\Entity\Compartenaire;
use App\Entity\Contrat;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('product', EntityType::class, [
                'class' => Product::class,
                'placeholder' => 'Sélectionnez un Produit',
                'choice_label' => 'nom',
                'disabled' => true,

            ])
            ->add('nom', Type\TextType::class, [
                'label' => 'Nom ',
                'disabled' => true,
                'attr' => [

                    'placeholder' => 'Tapez le Nom du Client'
                ],
                'required' => false,
            ])
            ->add('prenom', Type\TextType::class, [
                'label' => 'Prénom ',
                'disabled' => true,
                'attr' => [

                    'placeholder' => 'Tapez le Prénom du Client'
                ],
                'required' => false,
            ])
            ->add('raisonSociale', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'disabled' => true,
                'attr' => [

                    'placeholder' => 'Tapez le Raison sociale'
                ],
                'required' => false,
            ])
            ->add(
                'typeContrat',
                Type\ChoiceType::class,
                [
                    'label' => 'Type Contrat ',
                    'required' => false,
                    'disabled' => true,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Particulier' =>  'Particulier',
                        'Professionnel' => 'Professionnel',

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('typeProduct', Type\ChoiceType::class, [
                'label' => 'Type Produit',
                'required' => false,
                'disabled' => true,
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
            ->add(
                'activite',
                Type\ChoiceType::class,
                [
                    'label' => 'Activites ',
                    'required' => false,
                    'disabled' => true,
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


            ->add('comment',  Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Votre Commentaire",
                'required' => false,
            ])
            ->add('frais', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Frais",
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])






            ->add('NmbrReglement', Type\ChoiceType::class, [
                'label' => 'Nombre de règlements',
                'required' => false,
                'placeholder' => '--Selectie nombre de règlements -- ',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'attr' => [
                    'class' => 'form-control',
                    'data-controller' => 'reglement-updater'
                ],
            ])



            ->add('regelement', CollectionType::class, [
                'entry_type' => RegelementType::class,
                'allow_add' => true,
                'by_reference' => false,
                'prototype' => true,
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
