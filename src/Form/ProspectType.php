<?php

namespace App\Form;

use App\Entity\Prospect;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', Type\TextType::class, [
                'label' => 'Nom    (obligatoir)',
                'attr' => [

                    'placeholder' => 'Tapez le Nom du Client'
                ],
                'required' => true,
                // 'constraints' => new NotBlank(['message' => 'ne peut pas etre vide'])
            ])
            ->add('prenom', Type\TextType::class, [
                'label' => 'Prénom   (obligatoir) ',
                'attr' => [

                    'placeholder' => 'Tapez le Prénom du Client'
                ],
                'required' => false,

            ])
            ->add('phone', Type\TelType::class, [
                'label' => 'Téléphone 1    (obligatoir)',
                'required' => true,
                'constraints' => new Length([
                    'min' => 10,
                    'minMessage' => '  
                    le numéro de téléphone doit composer des 10 chiffres y a compris le 0 ',
                    'max' => 10,
                    'maxMessage' => '  
                    le numéro de téléphone doit composer des 10 chiffres y a compris le 0 '
                ]),

                'attr' => [
                    'placeholder' => 'Merci de saisir le numéro de téléphone'
                ]
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'Email (obligatoir)',
                'required' => true,
                'attr' => [
                    'placeholder' => "Merci de saisir l'adresse email"
                ]
            ])
             
            ->add('city', Type\TextType::class, [
                'label' => 'Ville ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville du client',
                ]
            ])
            ->add('adress', Type\TextareaType::class, [
                'label' => 'Addresse complét (obligatoir)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address compltét du client',
                ]
            ])
            ->add('brithAt', BirthdayType::class, [
                'label' => 'Date de Naissance ',
                'widget' => 'single_text',
                'required' => false,
            ])
            // ->add('source', Type\TextType::class, [
            //     'label' => 'Source',
            //     'required' => true,
            //     'data' => '1',  

            //     'attr' => ['readonly' => true],  
            // ])
            ->add('typeProspect', Type\ChoiceType::class, [
                'label' => 'Type Pospect ',
                'required' => true,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'Particulier' =>  '1',
                    'Professionnel' => '2',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison sociale ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])
            ->add('codePost', TextType::class, [
                'label' => 'Code Postal (obligatoir)',
                'constraints' => new Length(['min' => 5,  'minMessage' => 'le code postale doit etre quatre caactaire mini', 'max' => 5, 'maxMessage' => 'le code postale doite etre 5 caractaire max']),
                'attr' => [
                    'placeholder' => 'Merci de saisir le Code Postal',
                ]
            ])
             
            ->add('assurer', Type\ChoiceType::class, [
                'label' => 'Assuré actuellement',
                'required' => false,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non'
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('lastAssure', Type\ChoiceType::class, [
                'label' => 'Ancienne assurance résilié ',
                'required' => false,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non'
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add(
                'motifResil',
                Type\ChoiceType::class,
                [
                    'label' => 'Motif résiliation ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Aggravation de risque' =>  1,
                        'Amiable' =>  2,
                        'Échéance' => 3,
                        'Non-paiement' => 4,
                        'Sinistre' =>  5,
                        'Suspension de permis' => 6,
                        'Fausse déclaration' => 7
                    ],
                    'expanded' => false,
                    'multiple' => false
                ]
            )
            ->add('motifSaise', Type\ChoiceType::class, [
                'label' => 'Motive de saisir ',
                'required' => true,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'Parrainage' => '1',
                    'Appel Entrant' => '2',
                    'Avenant' => '3',
                    'Ancienne contrat' => '4',
                    'Propre site' => '5',
                    'Revendeur' => '6',
                    'Hors cible' => '7',
                    'Test' => '8',
                ],
                'expanded' => false,
                'multiple' => false
            ])
            // ->add('url')
            // ->add('secdEmail')
            // ->add('creatAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add(
                'activites',
                Type\ChoiceType::class,
                [
                    'label' => 'Activites ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'TPM' =>  1,
                        'VTC' =>  2,
                        'Sociéte' => 3,
                        'Décenale' => 4,
                        'Dommage' =>  5,
                        'Marchandise' =>  6,
                        'Négociant' =>  7,
                        'Prof auto' =>  8,
                        'Ecole' => 9,
                        'Garage' => 10,
                        'Location-vehicule' => 11,
                        'retraite' => 12,
                        'Taxi' => 13,

                    ],
                    'expanded' => false,
                    'multiple' => false,
                    // 'constraints' => [
                    //     new Callback([$this, 'validateActivites'])
                    // ]
                ]
            )
   

            ->add('product')
            ->add('team')
            ->add('comrcl')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
