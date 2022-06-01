<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BurgerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Nom',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ])
            ->add('prix', NumberType::class,[
                'attr'=> [
                    'class'=> 'form-control ',
                ],
                'label'=> 'Prix',
                
                'label_attr'=> [
                    'class'=>'form-label  '
                ],
            
            ])
            ->add('description', TextType::class,[
                'attr'=> [
                    'class'=> 'form-control '
                ],
                'label'=> 'Description',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ])
            ->add('image', FileType::class,[
                'attr'=> [
                    'class'=> ' img-burger mt-4'
                ],
                'label'=> false,
                'multiple'=> true,
                'mapped'=> false,
                'required'=> false,
               

            ])
/*             ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary mt-4 mb-2'
                ],
                'label'=>'Ajouter'
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
