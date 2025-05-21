<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Regelement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class RegelementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('frais', Type\MoneyType::class, [
        //         'attr' => ['class' => 'tinymce'],
        //         'label' => "Frais declarer",
        //         'required' => true,
        //         'attr' => [
        //             'placeholder' => 'Tapez en EURO',
        //             'divisor' => 100,

        //         ],

        //     ])
        //     ->add('montantReglement', Type\MoneyType::class, [
        //         'attr' => ['class' => 'tinymce'],
        //         'label' => "Montant du règlement",
        //         'required' => true,
        //         'attr' => [
        //             'placeholder' => 'Tapez en EURO',
        //             'divisor' => 100,

        //         ],

        //     ])
        //     ->add('montantReglement1', Type\MoneyType::class, [
        //         'attr' => ['class' => 'tinymce'],
        //         'label' => "Montant du règlement",
        //         'required' => true,
        //         'attr' => [
        //             'placeholder' => 'Tapez en EURO',
        //             'divisor' => 100,

        //         ],

        //     ])
        //     ->add('montantReglement2', Type\MoneyType::class, [
        //         'attr' => ['class' => 'tinymce'],
        //         'label' => "Montant du règlement",
        //         'required' => true,
        //         'attr' => [
        //             'placeholder' => 'Tapez en EURO',
        //             'divisor' => 100,

        //         ],

        //     ])
        //     ->add('montantReglement3', Type\MoneyType::class, [
        //         'attr' => ['class' => 'tinymce'],
        //         'label' => "Montant du règlement",
        //         'required' => true,
        //         'attr' => [
        //             'placeholder' => 'Tapez en EURO',
        //             'divisor' => 100,

        //         ],

        //     ])
        //     ->add('dateReglement', Type\DateType::class, ['label' => 'Date du règlement', 'required' => false, 'widget' => 'single_text'])

        //     ->add('dateReglement1', Type\DateType::class, ['label' => 'Date du règlement', 'required' => false, 'widget' => 'single_text'])

        //     ->add('dateReglement2', Type\DateType::class, ['label' => 'Date du règlement', 'required' => false, 'widget' => 'single_text'])

        //     ->add('dateReglement3', Type\DateType::class, ['label' => 'Date du règlement', 'required' => false, 'widget' => 'single_text'])

        //     ->add('transaction', Type\TextType::class, ['label' => 'Transaction N°', 'required' => false,])
        //     ->add(
        //         'moyen',
        //         Type\ChoiceType::class,
        //         [
        //             'label' => 'Moyen Paiement ',
        //             'required' => true,
        //             'disabled' => false,
        //             'placeholder' => '--Merci de selectie-- ',
        //             'choices' => [
        //                 'MANUEL' =>   'MANUEL',
        //                 'LIEN' =>   'LIEN',
        //                 'VIREMENT' =>   'VIREMENT',


        //             ],
        //             'expanded' => false,
        //             'multiple' => false,
        //         ]
        //     );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regelement::class,
        ]);
    }
}
