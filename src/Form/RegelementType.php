<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Regelement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class RegelementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantReglement', Type\NumberType::class, ['label' => 'Montant du règlement'])
            ->add('dateReglement', Type\DateType::class, ['label' => 'Date du règlement', 'widget' => 'single_text'])
            ->add('transaction', Type\TextType::class, ['label' => 'Transaction'])
            ->add(
                'moyen',
                Type\ChoiceType::class,
                [
                    'label' => 'Etat Contrat ',
                    'required' => false,
                    'disabled' => false,
                    'placeholder' => '--Merci de selectie-- ',
                    'choices' => [
                        'MANUEL' =>   'MANUEL',
                        'LIEN' =>   'LIEN',
                        'VIREMENT' =>   'VIREMENT',


                    ],
                    'expanded' => false,
                    'multiple' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regelement::class,
        ]);
    }
}
