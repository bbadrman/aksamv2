<?php

namespace App\Form;


use App\Entity\Regelement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class AjouterRegelementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transaction', Type\TextType::class, ['label' => 'Transaction N°', 'required' => true,])
            ->add('montantReglement', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Montant du règlement",

                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])
            ->add('dateReglement', Type\DateType::class, ['label' => 'Date du règlement',   'disabled' => true, 'widget' => 'single_text'])

            ->add(
                'moyen',
                Type\ChoiceType::class,
                [
                    'label' => 'Moyen Paiement ',

                    'disabled' => true,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'MANUEL' =>   'MANUEL',
                        'LIEN' =>   'LIEN',
                        'VIREMENT' =>   'VIREMENT',


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add('frais', Type\MoneyType::class, [
                'attr' => ['class' => 'tinymce'],
                'label' => "Frais declarer",
                'required' => true,
                'attr' => [
                    'placeholder' => 'Tapez en EURO',
                    'divisor' => 100,

                ],

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regelement::class,
        ]);
    }
}
