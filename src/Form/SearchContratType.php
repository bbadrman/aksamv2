<?php

namespace App\Form;

use App\Entity\Team;
use App\Repository\ClientRepository;
use App\Search\SearchClient;
use App\Repository\UserRepository;
use App\Search\SearchContrat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class SearchContratType extends AbstractType
{


    public function __construct(private EntityManagerInterface $entityManager, private UserRepository $userRepository, private Security $security) {}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



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


            ->add('r', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])
            ->add(
                'e',
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


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchContrat::class,
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
