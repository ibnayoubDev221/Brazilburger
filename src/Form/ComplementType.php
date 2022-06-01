<?php

namespace App\Form;

use App\Entity\Complement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ComplementType extends AbstractType
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
            ->add('prix',NumberType::class,[
                'attr'=> [
                    'class'=> 'form-control w-100'
                ],
                'label'=> 'Prix',
                'label_attr'=> [
                    'class'=>'form-label mt-4 '
                ]
            ])
            ->add('image', FileType::class,[
                'attr'=> [
                    'class'=> ' mt-4'
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
            'data_class' => Complement::class,
        ]);
    }
}
