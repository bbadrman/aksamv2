<?php

namespace App\Form;

use App\Entity\Prospect;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('phone')
            ->add('email')
            ->add('gender')
            ->add('city')
            ->add('adress')
            ->add('brithAt', null, [
                'widget' => 'single_text',
            ])
            ->add('source')
            ->add('typeProspect')
            ->add('raisonSociale')
            ->add('codePost')
            ->add('gsm')
            ->add('assurer')
            ->add('lastAssure')
            ->add('motifResil')
            ->add('motifSaise')
            ->add('url')
            ->add('secdEmail')
            ->add('creatAt', null, [
                'widget' => 'single_text',
            ])
            ->add('activites')
            ->add('comrcl', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
