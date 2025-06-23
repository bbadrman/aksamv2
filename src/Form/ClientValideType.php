<?php

namespace App\Form;


use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ClientValideType extends AbstractType
{
    /** 
     * 
     * @return void
     */
    public function __construct(private Security $security) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $roles = $user->getRoles();

        $builder->add('nom', Type\TextType::class, [
            'label' => 'Prénom',
            'required' => false,
            'attr' => [
                'placeholder' => 'Merci de saisir le prénom du client'
            ]
        ])
            ->add('prenom', Type\TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom du client'
                ]
            ])
            ->add('phone', Type\TextType::class, [
                'label' => 'Téléphone 2',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le numéro de téléphone'
                ]
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => "Merci de saisir l'adresse email"
                ]
            ])
            ->add('adress', Type\TextareaType::class, [
                'label' => 'Addresse complét *',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address compltét du client',
                ]
            ])
            ->add('raisonSociale', Type\TextType::class, [
                'label' => 'Raison sociale ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ]
            ])
            // ->add('forceJuridique', Type\ChoiceType::class, [
            //     'label' => 'formes juridiques',
            //     'required' => true,
            //     'placeholder' => '--Merci de selectie-- ',
            //     'choices' => [
            //         'SARL' =>  '1',
            //         'EI' => '2',
            //         'EURL' => '3',
            //         'SA' => '4',
            //         'SAS' => '5',
            //         'SASU' => '6',
            //         'SNC' => '7',
            //         'SCOP' => '8',
            //         'Association' => '9',

            //     ],
            //     'expanded' => false,
            //     'multiple' => false,
            // ])




            ->add('comment', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Remarque",
                'required' => false,
            ]);
    }
    /**  
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
