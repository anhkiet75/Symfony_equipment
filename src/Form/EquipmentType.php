<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Equipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('serial_number',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter serial_number...'
                ]
            ])
            ->add('name',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter name...'
                ]
            ])
            ->add('description',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter description'
                ]
            ])
            ->add('status',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter status...'
                ]
            ])
            ->add('category',EntityType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => Category::class,
                'mapped' => true,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
