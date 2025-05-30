<?php

namespace App\Form;

use App\Entity\Sav;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class SavType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur actuel n\'est pas de type User.');
        }

        $builder

            ->add(
                'natureDemande',
                Type\ChoiceType::class,
                [
                    'label' => 'Nature Demande ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Avenant' =>  1,
                        'Remboursement' =>  2,
                        'Sinistre' => 3,
                        'Demande documents' => 4
                    ],
                    'expanded' => false,
                    'multiple' => false,

                ]
            )
            ->add(
                'motif',
                Type\ChoiceType::class,
                [
                    'label' => 'Motif ',
                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Changement adresse' =>  1,
                        'Changement RIB ' =>  2,
                        'Changement véhicule' => 3,
                        'Frais dossier' => 4,
                        'Acompte' => 5,
                        'Prorata suite résiliation' => 6,
                        'Ouverture sinistre' => 7,
                        'Mandaté expert' => 8,
                        'Remboursement sinistre' => 9,
                        'Relevé information' => 10,
                        'Attestation' => 11,
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    // 'constraints' => [
                    //     new Callback([$this, 'validateActivites'])
                    // ]
                ]
            )
            ->add('comment', Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Votre Commentaire",
                'required' => false
            ])
            ->add('afect', EntityType::class, [
                'class' => User::class,
                'label' => 'Affect',
                'placeholder' => '--Merci de sélectionner--',


                'query_builder' => function (UserRepository $er) use ($user) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id = :current_user_id')
                        ->orwhere('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_STAND%')
                        ->setParameter('current_user_id', $user->getId());
                },
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sav::class,
        ]);
    }
}
