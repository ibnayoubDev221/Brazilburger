<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Burger;
use App\Entity\Complement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MenuType extends AbstractType
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

            ->add('burger', EntityType::class,[
                'class'=>  Burger::class,
                'choice_label'=>'nom',
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Burger',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ]) 
            ->add('complement', EntityType::class,[
                'class'=>  Complement::class,
                'choice_label'=>'nom',
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Complement',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ]) 
            ->add('description', TextType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Description',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ])
            ->add('image', FileType::class,[
                'attr'=> [
                    'class'=> ' mt-3 mb-2 '
                ],
                'label'=> false,
                'multiple'=> true,
                'mapped'=> false,
                'required'=> false,
               

            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
