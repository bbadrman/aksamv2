<?php

namespace App\Form;

use App\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as TypeDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RelanceProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relance', Type\ChoiceType::class, [
                'label' => 'Motif Relance ',
                'required' => true,
                'placeholder' => '       ',
                'choices' => [
                    'Prise de Contact' => [
                        'Rendez-vous' => '1',
                        'Injoignable' => '2',

                    ],
                    'Attente DOC' => '3',
                    'Tarification' => '4',
                    'Prise de Décision ' => '5',
                    'Cloture ' => [
                        'Faux Fiche' => '6',
                        'Doublon' => '7',
                        'Passage Concurrent ' => '8',
                        'Passage Client ' => '9',
                        'Déjà Souscrit' => '10',
                        'Toujour Injoignable' => '11',
                        'Hors Cible' => '12',
                        'Test' => '13',
                    ],

                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('comment', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Remarque",
                'required' => false,
            ])
            ->add('relanceAt', TypeDateTimeType::class, [
                'label' => 'Date de Relance *',
                'required' => false,
                'widget' => 'single_text', // Utiliser un champ de saisie unique

                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d H:i')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
