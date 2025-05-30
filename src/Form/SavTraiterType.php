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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SavTraiterType extends AbstractType
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
        // il faut supremer 
        $builder

            ->add(
                'motifRelance',
                Type\ChoiceType::class,
                [

                    'required' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'Prise de Contact' => [
                            'RDV' =>  1
                        ],
                        'Relance' => [
                            'Attente doc' =>  2,
                            'Attente réponse CIE' => 3,
                        ],

                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]

            )
            ->add(
                'relanceAt',
                DateTimeType::class,
                [
                    'label' => 'Date de Relance *',
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => [
                        'min' => (new \DateTime())->format('Y-m-d H:i')
                    ]
                ]
            )
            ->add('comentTraiter', Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Votre Commentaire",
                'required' => false
            ])
            ->add('status', Type\ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'placeholder' => '--Merci de Choisir le genre--',
                'choices' => [
                    'SAV Traité' => 1,
                    'Clôture SAV' => 2
                ],
                'expanded' => false,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sav::class,
        ]);
    }
}
