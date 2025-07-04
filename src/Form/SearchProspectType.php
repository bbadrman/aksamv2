<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use App\Search\SearchProspect;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchProspectType extends AbstractType
{

    public function __construct(private EntityManagerInterface $entityManager, private  UserRepository $userRepository, private Security $security) {}
    /**  
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder,  array $options): void
    {

        $teamRepository = $this->entityManager->getRepository(Team::class);
        $teams = $teamRepository->findAll();
        $teamChoices = [];
        foreach ($teams as $team) {
            $teamChoices[$team->getName()] = $team->getName();
        }



        $user = $this->security->getUser();
        if ($user  instanceof User) {
            $teams = $user->getTeams(); // Assurez-vous que cette méthode existe et retourne l'équipe de l'utilisateur

            if (in_array('ROLE_DEV', $user->getRoles(), true) || in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                $comrclsForTeam = $this->userRepository->findAll();
            } else if (in_array('ROLE_CHEF', $user->getRoles(), true) && $team) {
                $comrclsForTeam = $team === null ? [] :  $this->userRepository->findComrclByteamOrderedByAscName($user);
            } else {
                // cmrcl peut voir seulement les non traités attachés à lui
                $comrclsForTeam =  [];
            }

            // Transformez la liste de commerciaux en un tableau utilisable pour les choix dans le formulaire
            $comrclChoices = [];
            foreach ($comrclsForTeam as $comrcl) {
                $comrclChoices[$comrcl->getUsername()] = $comrcl->getUsername();
            }
        }
        // $userRepository = $this->entityManager->getRepository(User::class);
        // $comrcls = $userRepository->findAll();
        // $comrclChoices = [];
        // foreach ($comrcls as $comrcl) {
        //     $comrclChoices[$comrcl->getUsername()] = $comrcl->getUsername();
        // }

        $builder
            ->add('q', Type\TextType::class, [

                'label' => "Nom :",
                'attr' => [
                    'placeholder' => "Nom."
                ],

                'required' => false
            ])
            ->add('m', Type\TextType::class, [

                'label' => "Prenom :",
                'attr' => [
                    'placeholder' => "Prenom."
                ],
                'required' => false
            ])
            ->add('g', Type\TextType::class, [
                'label' => "E-mail :",
                'attr' => [
                    'placeholder' => "E-mail."
                ],
                'required' => false
            ])
            ->add('c', Type\TextType::class, [
                'label' => "Ville :",
                'attr' => [
                    'placeholder' => "Ville."
                ],
                'required' => false
            ])
            ->add('l', Type\TextType::class, [
                'label' => "Telephone :",
                'attr' => [
                    'placeholder' => "Telephone."
                ],
                'required' => false
            ])

            ->add('team', Type\ChoiceType::class, [
                'label' => "Equipe :",
                'placeholder' => '--Selectie-- ',
                'choices' => $teamChoices,
                'required' => false
            ])

            ->add('d',  Type\DateType::class, [
                'widget' => 'single_text',
                'label' => "du :",
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
                'label' => "au :",
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


            ->add('r', Type\ChoiceType::class, [
                'label' => "commercial :",
                'placeholder' => '--Selectie-- ',
                'choices' => $comrclChoices,
                'required' => false
            ])
            ->add('s', Type\TextType::class, [
                'label' => "r-sociale :",
                'attr' => [
                    'placeholder' => "R-sociale."
                ],
                'required' => false
            ])

            ->add('source', Type\ChoiceType::class, [
                'label' => 'Source :',
                'required' => false,
                'placeholder' => '--Selectie-- ',
                'choices' => [

                    'Saisie manuelle' => '1',
                    'Revendeur' => '2',
                    'Propre site' => '3',

                ],

                'expanded' => false,
                'multiple' => false
            ])
            ->add('dr', Type\DateType::class, [
                'label' => "Relance Du :",

                'widget' => 'single_text',

                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => false
            ])

            ->add('ddr', Type\DateType::class, [
                'label' => "Ou :",

                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => "date format: yyyy-mm-dd."
                ],
                'required' => false
            ])
            ->add('relance', Type\ChoiceType::class, [
                'label' => 'Motif Relance ',
                'required' => false,
                'placeholder' => '--Selectie-- ',
                'choices' => [
                    'Prise de Contact' => [
                        'Rendez-vous' => '1',
                        'Injoignable' => '2',

                    ],
                    'Attente DOC' => '3',
                    'Tarification' => '4',
                    'Prise de Décision ' => '5',
                    'Cloture ' => [
                        'Faux Fiche' => '6',
                        'Doublon' => '7',
                        'Passage Concurrent ' => '8',
                        'Passage Client ' => '9',
                        'Déjà Souscrit' => '10',
                        'Toujour Injoignable' => '11',
                        'Test' => '13',
                    ],

                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('u', Type\ChoiceType::class, [
                'label' => 'Propre Site ',
                'required' => false,
                'placeholder' => '--Selectie-- ',
                'choices' =>   [
                    'des-vtc' => '1',
                    'garage-pro' => '2',
                    'pour-taxi' => '3',
                    'pour-vtc' => '4',
                    'des-resilies' => '5',
                    'decennale' => '6',
                    'comparez' => '7',
                    'camion' => '8',
                    'flotte' => '9',
                    'vehicule-pro' => '10',
                    'transporteurs' => '11',
                    'vehicules-prof' => '12',
                    'engins' => '13',
                    'prof-auto' => '14',
                    'auto-ecole' => '15',
                    'negociants-auto' => '16',
                    'garage-auto' => '17',

                ],


                'expanded' => false,
                'multiple' => false
            ]);
        // ->add('rest', Type\ResetType::class, [
        //     'label' => "Rest"
        // ]);

        //Ajoutez la validation personnalisée pour s'assurer qu'au moins un champ est rempli
        // $builder->add('validate_at_least_one', Type\HiddenType::class, [
        //     'mapped' => false,
        //     'constraints' => [
        //         new Callback([$this, 'validateAtLeastOneField']),
        //     ],
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProspect::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    // public function getBlockPrefix(): string
    // {
    //     return '';
    // }

    // public function validateAtLeastOneField($value, ExecutionContextInterface $context)
    // {
    //     $formData = $context->getRoot()->getData();

    //     // Liste des champs à vérifier
    //     $fieldsToCheck = ['q', 'm', 'g', 'c', 'l', 'team', 'd', 'dd', 'r', 's', 'source', 'dr', 'ddr', 'motifRelanced'];

    //     $fieldsFilledCount = 0;

    //     // Vérifiez combien de champs sont remplis
    //     foreach ($fieldsToCheck as $field) {
    //         if (!empty($formData->{$field})) {
    //             $fieldsFilledCount++;
    //         }
    //     }

    //     // Si aucun champ n'est rempli, ajoutez une violation de la contrainte
    //     if ($fieldsFilledCount === 0) {
    //         $context->buildViolation("Au moins un champ doit être rempli.")
    //             ->atPath('q') // Remplacez 'q' par un champ de votre choix
    //             ->addViolation();
    //     }
    // }
}
