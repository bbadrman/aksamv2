<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Document;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bulletinAdhesion', Type\ChoiceType::class, [
                'label' => 'Bulletin d\'Adhésion',
                'required' => false,
                'placeholder' => false, // pour cacher le choix None 
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('mondatSepa', Type\ChoiceType::class, [
                'label' => 'Mandat Sepa',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('conditionGeneral', Type\ChoiceType::class, [
                'label' => 'Condition General',
                'required' => false,
                'placeholder' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])


            ->add('devoirConseil', Type\ChoiceType::class, [
                'label' => 'Devoir Conseil',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('permis', Type\ChoiceType::class, [
                'label' => 'Permis',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('kbis', Type\ChoiceType::class, [
                'label' => 'Kbis',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('releveInformation', Type\ChoiceType::class, [
                'label' => 'Releve d\'Information',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('deviSigne', Type\ChoiceType::class, [
                'label' => 'Devis Signé',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('CarteGriseDefinitive', Type\ChoiceType::class, [
                'label' => 'Carte Grise Definitive',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('AtestNonAssure', Type\ChoiceType::class, [
                'label' => 'Attestation Non Assurance',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('AtestNonSinist', Type\ChoiceType::class, [
                'label' => 'Attestation Non Sinistre',
                'placeholder' => false,
                'required' => false,
                'choices' => [
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'expanded' => true,
                'multiple' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
