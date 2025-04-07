<?php

namespace App\Form;

use App\Entity\Compartenaire;
use App\Entity\Contrat;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContratEtatType extends AbstractType
{
    public function __construct(private Security $security) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

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
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
