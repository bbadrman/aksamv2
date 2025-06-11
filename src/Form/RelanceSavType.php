<?php

namespace App\Form;

use App\Entity\RelanceSav;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RelanceSavType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'motifRelance',
                Type\ChoiceType::class,
                [
                    'label' => 'Relancer',
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
                        'Clôture SAV' => [
                            'Clôture SAV' =>  4,
                        ]
                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]

            )
            ->add(
                'creatAt',
                DateTimeType::class,
                [
                    'label' => 'Date de Relance ',
                    'required' => true,
                    'widget' => 'single_text',
                    'attr' => [
                        'min' => (new \DateTime())->format('Y-m-d H:i')
                    ]
                ]
            )
            ->add('comment', Type\TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Votre Commentaire",
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RelanceSav::class,
        ]);
    }
}
