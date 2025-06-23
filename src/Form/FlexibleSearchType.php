<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class FlexibleSearchType extends AbstractType
{
    private TeamRepository $teamRepository;
    private UserRepository $userRepository;

    public function __construct(TeamRepository $teamRepository, UserRepository $userRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Dates (obligatoires)
            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionner la date de début'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de début est obligatoire']),
                    new Assert\Type(['type' => '\DateTimeInterface'])
                ]
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionner la date de fin'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de fin est obligatoire']),
                    new Assert\Type(['type' => '\DateTimeInterface']),
                    new Assert\GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[startDate].data',
                        'message' => 'La date de fin doit être supérieure ou égale à la date de début'
                    ])
                ]
            ])

            // Filtres par équipe (optionnel)
            ->add('teams', EntityType::class, [
                'label' => 'Équipes',
                'class' => Team::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionner les équipes (toutes par défaut)'
                ],
                'query_builder' => function () {
                    return $this->teamRepository->createQueryBuilder('t')
                        ->where('t.active = true')
                        ->orderBy('t.name', 'ASC');
                }
            ])

            // Filtres par utilisateur (optionnel)
            ->add('users', EntityType::class, [
                'label' => 'Commerciaux',
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionner les commerciaux (tous par défaut)'
                ],
                'query_builder' => function () {
                    return $this->userRepository->createQueryBuilder('u')
                        ->leftJoin('u.team', 't')
                        ->where('u.active = true')
                        ->orderBy('t.name', 'ASC')
                        ->addOrderBy('u.username', 'ASC');
                }
            ])

            // Filtres par source
            ->add('sources', ChoiceType::class, [
                'label' => 'Sources',
                'choices' => [
                    'Saisir' => 1,
                    'Site Pub' => null,
                    'Revendeur' => 2,
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'filter-checkboxes'
                ]
            ])

            // Filtres par type de prospect
            ->add('typeProspects', ChoiceType::class, [
                'label' => 'Types de prospects',
                'choices' => [
                    'Particulier' => 1,
                    'Professionnel' => 2,
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => [
                    'class' => 'filter-checkboxes'
                ]
            ])

            // Filtres par motif de saisie
            ->add('motifsSaise', ChoiceType::class, [
                'label' => 'Motifs de saisie',
                'choices' => [
                    'Parrainage' => 1,
                    'Appel' => 2,
                    'Avenant' => 3,
                    'Ancienne' => 4,
                    'Propre site' => 5,
                    'Revendeur' => 6,
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionner les motifs'
                ]
            ])

            // Filtres par activité
            ->add('activites', ChoiceType::class, [
                'label' => 'Activités',
                'choices' => [
                    'TPM' => 1,
                    'VTC' => 2,
                    'Société' => 3,
                    'Décennale' => 4,
                    'Dommage' => 5,
                    'Marchandise' => 6,
                    'Négociant' => 7,
                    'Prof Auto' => 8,
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionner les activités'
                ]
            ])

            // Boutons d'action
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg',
                    'icon' => 'fas fa-search'
                ]
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Réinitialiser',
                'attr' => [
                    'class' => 'btn btn-secondary',
                    'icon' => 'fas fa-undo'
                ]
            ])
            ->add('export', SubmitType::class, [
                'label' => 'Exporter CSV',
                'attr' => [
                    'class' => 'btn btn-success',
                    'icon' => 'fas fa-download',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return ''; // Pas de préfixe pour avoir des URLs plus propres
    }
}

// DTO pour les critères de recherche
class SearchCriteria
{
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;
    private array $teams = [];
    private array $users = [];
    private array $sources = [];
    private array $typeProspects = [];
    private array $motifsSaise = [];
    private array $activites = [];

    // Getters et Setters
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getTeams(): array
    {
        return $this->teams;
    }

    public function setTeams(array $teams): self
    {
        $this->teams = $teams;
        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function setSources(array $sources): self
    {
        $this->sources = $sources;
        return $this;
    }

    public function getTypeProspects(): array
    {
        return $this->typeProspects;
    }

    public function setTypeProspects(array $typeProspects): self
    {
        $this->typeProspects = $typeProspects;
        return $this;
    }

    public function getMotifsSaise(): array
    {
        return $this->motifsSaise;
    }

    public function setMotifsSaise(array $motifsSaise): self
    {
        $this->motifsSaise = $motifsSaise;
        return $this;
    }

    public function getActivites(): array
    {
        return $this->activites;
    }

    public function setActivites(array $activites): self
    {
        $this->activites = $activites;
        return $this;
    }

    /**
     * Convertit les critères en tableau pour les repositories
     */
    public function toArray(): array
    {
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'teamIds' => array_map(fn($team) => $team->getId(), $this->teams),
            'userIds' => array_map(fn($user) => $user->getId(), $this->users),
            'sources' => $this->sources,
            'typeProspects' => $this->typeProspects,
            'motifsSaise' => $this->motifsSaise,
            'activites' => $this->activites,
        ];
    }

    /**
     * Vérifie si les critères sont valides
     */
    public function isValid(): bool
    {
        return $this->startDate !== null &&
            $this->endDate !== null &&
            $this->startDate <= $this->endDate;
    }

    /**
     * Génère une clé de cache basée sur les critères
     */
    public function getCacheKey(): string
    {
        $data = $this->toArray();
        // Retirer les valeurs nulles et vides
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== [];
        });

        return 'search_' . md5(serialize($data));
    }
}
