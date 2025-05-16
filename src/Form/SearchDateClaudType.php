<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class SearchDateClaudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime();

        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une date de début'
                    ]),
                    new LessThanOrEqual([
                        'propertyPath' => 'parent.all[endDate].data',
                        'message' => 'La date de début doit être antérieure ou égale à la date de fin'
                    ])
                ]
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une date de fin'
                    ]),
                    new GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[startDate].data',
                        'message' => 'La date de fin doit être postérieure ou égale à la date de début'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'attr' => ['novalidate' => 'novalidate'] // Pour activer la validation HTML5
        ]);
    }
}
