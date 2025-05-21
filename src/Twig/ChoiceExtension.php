<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChoiceExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_choice_options', [$this, 'getChoiceOptions']),
        ];
    }

    public function getChoiceOptions(string $type)
    {
        $choices = [
            'url' => [

                '1' => 'des-vtc',
                '2' => 'garage-pro',
                '3' => 'pour-taxi',
                '4' => 'pour-vtc',
                '5' => 'des-resilies',
                '6' => 'decennale',
                '7' => 'comparez',
                '8' => 'camion',
                '9' => 'flotte',
                '10' => 'vehicule-pro',
                '11' => 'transporteurs',
                '12' => 'vehicules-prof',
                '13' => 'engins',
                '14' => 'prof-auto',
                '15' => 'auto-ecole',
                '16' => 'negociants-auto',
                '17' => 'garage-auto',
                // ...
            ],
            'activites' => [

                1 => 'TPM',
                2 => 'VTC',
                3 => 'Sociéte',
                4 => 'Décenale',
                5 => 'Dommage',
                6 => 'Marchandise',
                7 => 'Négociant',
                8 => 'Prof auto',
                9 => 'Garage'
            ],
            'typeProspect' => [
                '1' => 'Particulier',
                '2' => 'Professionnel'
            ],
            'source' => [
                '1' => 'Saisie manuelle',
                '2' => 'Revendeur',
                '3' => 'Propre site',


            ],
            'relance' => [
                '1' => 'Rendez-vous',
                '2' => 'Injoignable',
                '3' => 'Attente DOC',
                '4' => 'Tarification',
                '5' => 'Prise de Décision',
                '6' => 'Faux Fiche',
                '7' => 'Doublon',
                '8' => 'Passage Concurrent',
                '9' => 'Passage Client',
                '10' => 'Déjà Souscrit',
                '11' => 'Toujour Injoignable',
                '12' => 'Tarification',
                '13' => 'Test',
            ]
        ];

        return $choices[$type] ?? [];
    }
}
