<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use App\Search\SearchUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateMin', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date souscription min',
            ])
            ->add('dateMax', Type\DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date souscription max',
            ])

            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'multiple' => false,
                'required' => false,

            ])
            ->add('username', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => false,
                'required' => false,

            ])
            ->add('contrat', TextType::class, [
                'required' => false,
                'label' => 'Contrat',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchUser::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
