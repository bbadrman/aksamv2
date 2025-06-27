<?php

namespace App\Form;

use App\Entity\Prospect;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class GsmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gsm', TelType::class, [
                'label' => 'GSM 2',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir le deuxième numéro de téléphone'
                ]
            ]);
        //pour reformater le numero nationnal
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT, // Use PRE_SUBMIT or SUBMIT_PREVALIDATE for cleaning user input
            function (FormEvent $event) {
                $data = $event->getData();

                // Handle null or empty values
                if (empty($data['gsm'])) {
                    return;
                }

                // Convert the phone number to international format
                $formattedGsm = $this->convertPhoneNumberToInternational($data['gsm']);

                // Update the form data with the formatted phone number
                $event->setData(['gsm' => $formattedGsm]);
            }
        );
    }

    private function convertPhoneNumberToInternational($phoneNumber)
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Check the length of the number to ensure it is valid
        if (strlen($phoneNumber) === 10) {
            // Check if it starts with '0' (local format)
            if (substr($phoneNumber, 0, 1) === '0') {
                // Replace '0' with '33' to get the international format
                return '+33' . substr($phoneNumber, 1);
            }
        }

        // Return original number if it doesn't need conversion
        return $phoneNumber;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
