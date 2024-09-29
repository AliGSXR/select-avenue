<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
            'label' => 'Votre prénom',
            'attr' => [
                'placeholder' => 'Entrez votre prénom'
            ]
        ])

            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse mail',
                ]

            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 15,
                            'minMessage' => 'Mot de passe trop court ( doit contenir au minimum 8 caractères )',
                            'maxMessage' => 'Mot de passe trop long ( doit contenir au maximum 15 caractères )',

                        ]
                    )
                ],
                'first_options'  => ['label' => 'Veuillez entrer votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Entrez votre mot de passe',
                    ],
                    'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Veuillez confirmer votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmer votre mot de passe',
                    ]
                ],
                'mapped' => false,

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
              new UniqueEntity([
                  'entityClass' => User::class,
                  'fields' => ['email'],
              ])
            ],
            'data_class' => User::class,
        ]);
    }
}
