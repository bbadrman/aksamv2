<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\UniteTravail;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Description de la fonction",
                'required' => false
            ])
            // ->add('uniteTravails', EntityType::class, [
            //     'class' => UniteTravail::class,
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            //     'required' => false,
            // ])
            // ->add('users', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'username',
            //     'multiple' => true,
            //     'required' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
