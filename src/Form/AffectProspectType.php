<?php

namespace App\Form;

use App\Entity\Prospect;
use App\Entity\User;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as TypeDateTimeType;

class AffectProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relance', Type\ChoiceType::class, [
                'label' => 'Motif Relance ',
                'required' => false,
                'placeholder' => '       ',
                'choices' => [
                    'Prise de Contact' => [
                        'Rendez-vous' => '1',
                        'Injoignable' => '12',

                    ],
                    'Attente DOC' => '4',
                    'Tarification' => '5',
                    'Prise de Décision ' => '6',
                    'Cloture ' => [
                        'Faux Fiche' => '7',
                        'Doublon' => '8',
                        'Passage Concurrent ' => '9',
                        'Passage Client ' => '10',
                        'Déjà Souscrit' => '3',
                        'Test' => '11',
                        'Toujour Injoignable' => '2',
                    ],

                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('relanceAt', TypeDateTimeType::class, [
                'label' => 'Date de Relance *',
                'required' => false,
                'widget' => 'single_text',
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
