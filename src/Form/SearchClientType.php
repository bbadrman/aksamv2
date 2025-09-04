<?php

namespace App\Form;

use App\Entity\Team;
use App\Search\SearchClient;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SearchClientType extends AbstractType
{


    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private Security $security,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $teams = $teamRepository->findAll();
        $teamChoices = [];
        foreach ($teams as $team) {
            $teamChoices[$team->getName()] = $team->getName();
        }

        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('L\'utilisateur n\'est pas connecté.');
        }
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $comrclsForTeam = $this->userRepository->findAll();
        } else if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
            $comrclsForTeam = $this->userRepository->findComrclByteamOrderedByAscName($user);
        } else {

            $comrclsForTeam =  [];
        }
        $comrclChoices = [];
        foreach ($comrclsForTeam as $comrcl) {
            $comrclChoices[$comrcl->getUsername()] = $comrcl->getUsername();
        }


        $builder
            ->add('f', Type\TextType::class, [
                'label' => 'Nom',
                'required' => false,

                'attr' => [
                    'placeholder' => 'Merci de saisir le nom du client'
                ]
            ])
            ->add('l', Type\TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le prénom du client'
                ]
            ])
            ->add('t', Type\TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le numéro de téléphone'
                ]
            ])
            ->add('g', Type\EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => "Merci de saisir l'adresse email"
                ]
            ])

            ->add('r', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])
            ->add('team', Type\ChoiceType::class, [
                'label' => "Equipe :",
                'placeholder' => '--Selectie-- ',
                'choices' => $teamChoices,
                'required' => false
            ])
            ->add('k', Type\ChoiceType::class, [
                'label' => "Commercial :",
                'placeholder' => '--Selectie-- ',
                'choices' => $comrclChoices,
                'required' => false
            ])->add('d', Type\DateType::class, [
                'label' => "Date client Du :",

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
            ->add('dr', Type\DateType::class, [
                'label' => "Date contrat du :",

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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchClient::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    // il faut cahe cette function if you wanted to table change of page 
    // pour afficher la page suivant , si no la table toujour reste en meme page
    // public function getBlockPrefix(): string
    // {
    //     return '';
    // }
}
