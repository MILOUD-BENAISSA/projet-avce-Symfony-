<?php

namespace App\Form;

use App\DTO\paiment;
use App\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaimentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('address', AddressType::class)
        ->add('cardNumber', TextType::class, [
            'label' => 'Numéro de carte : ',
            'required' => true,
        ])
        ->add('expirationMonth', TextType::class, [
            'label' => 'Mois d\'éxpiration : ',
            'required' => true,
        ])
        ->add('expirationYear', TextType::class, [
            'label' => 'Année d\'éxpiration : ',
            'required' => true,
        ])
        ->add('cvc', TextType::class, [
            'label' => 'Cryptogramme de sécurité : ',
            'required' => true,
        ])
        ->add('send', SubmitType::class, [
            'label' => 'Payer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => paiment::class
        ]);
    }
}
