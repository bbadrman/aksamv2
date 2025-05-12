<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Document;
use App\Entity\User;
use App\Search\SearchDocument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class SearchDocumentType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('contrat', EntityType::class, [
                'class' => Contrat::class,
                'choice_label' => 'nom', // ou un autre champ lisible
                'required' => false,
                'label' => 'Contrat'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // ou 'email', selon ce que vous avez
                'required' => false,
                'label' => 'Commercial'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchDocument::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
