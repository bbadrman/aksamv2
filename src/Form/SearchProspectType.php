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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

            if (in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true) || in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                $comrclsForTeam = $this->userRepository->findAll();
            } else if (in_array('ROLE_TEAM', $user->getRoles(), true) && $team) {
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

            ->add('d', Type\DateType::class, [
                'label' => "Du :",

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


            ->add('r', Type\ChoiceType::class, [
                'label' => "commercial :",
                'placeholder' => '--Selectie-- ',
                'choices' => $comrclChoices,
                'required' => false
            ])
            ->add('s', Type\TextType::class, [
                'label' => "r-sociale :",
                'attr' => [
                    'placeholder' => "Recherche par r-sociale."
                ],
                'required' => false
            ])

            ->add('source', Type\ChoiceType::class, [
                'label' => 'Source :',
                'required' => false,
                'placeholder' => '--Selectie-- ',
                'choices' => [
                    'Propre site' => '3',
                    'Saisie manuelle' => '1',
                    'Revendeur' => '2',
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
            ->add('motifRelanced', Type\ChoiceType::class, [
                'label' => 'Motif Relance ',
                'required' => false,
                'placeholder' => '--Selectie-- ',
                'choices' => [
                    'Prise de Contact' => [
                        'Rendez-vous' => '1',
                        'Injoignable' => '12',

                    ],
                    'Attente DOC' => '4',
                    'Tarification' => '5',
                    'Prise de Décision ' => '6',
                    'Cloture ' => [
                        'Faux Fiche' => '7',
                        'Doublon' => '8',
                        'Passage Concurrent ' => '9',
                        'Passage Client ' => '10',
                        'Déjà Souscrit' => '3',
                        'Test' => '11',
                        'Toujour Injoignable' => '2',
                    ],
                ],
                'expanded' => false,
                'multiple' => false
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
