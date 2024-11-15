<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'=> 'Votre prénom',
                'attr'=>[
                    'placeholder'=> 'Indiquez votre prénom....'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Votre nom',
                'attr'=>[
                    'placeholder'=> 'Indiquez votre nom....'
                ]
            ])
            ->add('adress',TextType::class, [
                'label'=> 'Votre adresse',
                'attr'=> [
                    'placeholder'=> 'Indiquez votre adresse....'
                ]
            ])
            ->add('postal', TextType::class, [
                'label'=> 'Votre Code postal',
                'attr'=>[
                    'placeholder'=> 'Indiquez votre Code postal....'
                ]
            ])
            ->add('city', TextType::class, [
                'label'=> 'Votre ville',
                'attr'=>[
                    'placeholder'=> 'Indiquez votre ville....'
                ]
            ])
            ->add('country', CountryType::class,[
                'label'=> 'Votre pays',
            ])
            ->add('phone', TextType::class, [
                'label'=> 'Votre numéro de téléphone',
                'attr'=>[
                    'placeholder'=>'Indiquez votre numéro de téléphone.....'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
            'csrf_protection' => true, // Active la protection CSRF
            'csrf_field_name' => '_token', // Nom du champ CSRF
            'csrf_token_id'   => 'adress_item', // Identifiant unique pour le token CSRF
        ]);
    }
}
