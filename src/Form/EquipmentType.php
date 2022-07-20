<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Equipment;
use App\Entity\Assign;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
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
            ->add('name',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter name...',
                ],
                'required' => false,
                'empty_data' => ''
            ])
            ->add('description',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter description'
                ],
                'required' => false,
                'empty_data' => ''
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
