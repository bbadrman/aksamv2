<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class ClientRelanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('phone', Type\TextType::class, [
                'label' => 'Téléphone 2',
                'required' => true,
                'disabled' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le numéro de téléphone'
                ]
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'disabled' => false,
                'attr' => [
                    'placeholder' => "Merci de saisir l'adresse email"
                ]
            ])
            ->add('raisonSociale', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'required' => true,
                'disabled' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])

            ->add('forceJuridique', Type\ChoiceType::class, [
                'label' => 'formes juridiques',
                'required' => true,
                'placeholder' => '--Merci de selectie-- ',
                'choices' => [
                    'SARL' =>  '1',
                    'EI' => '2',
                    'EURL' => '3',
                    'SA' => '4',
                    'SAS' => '5',
                    'SASU' => '6',
                    'SNC' => '7',
                    'SCOP' => '8',
                    'Association' => '9',

                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('adress', Type\TextareaType::class, [
                'label' => 'Addresse complét *',
                'required' => true,
                'disabled' => false,
                'attr' => [
                    'placeholder' => 'Address compltét du client',
                ]
            ])
            ->add('comment', Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Remarque",
                'required' => true,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
