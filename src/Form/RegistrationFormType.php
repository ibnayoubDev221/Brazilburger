<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email',TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'email',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
             ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label_attr'=> ['class'=>'form-label mt-4 mb-2 '],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
        
                    'attr'=> [
                        'class'=> 'form-control w-100'
                    ],
                    'label'=> 'Password',
                    'label_attr'=> [
                        'class'=>'form-label mt-4 '
                    ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class'=> 'form-control w-100'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('prenom',TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Prenom',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
             ])
            ->add('nom',TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Nom',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
             ])

             ->add('telephone',TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Telephone',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
             ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
