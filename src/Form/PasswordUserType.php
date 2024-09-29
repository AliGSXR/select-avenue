<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add ('actualPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Entrer votre mot de passe actuel',
                ],
                'mapped' => false

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
                'first_options'  => ['label' => 'Veuillez entrer votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Entrez votre nouveau mot de passe',
                    ],
                    'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Veuillez confirmer votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmer votre nouveau mot de passe',
                    ]
                ],
                'mapped' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confimer modification',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];


                //RECUP MDP SAISI PAR USER

                $hashedPassword = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                //Si c'est different
                if(!$hashedPassword){
                    $form->get('actualPassword')->addError(new formError("Mot de passe actuel incorrect"));
                }
        })

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
