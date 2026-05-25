<?php

namespace App\Form;

use App\Entity\Data;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', ChoiceType::class, [
                'label' => 'Rodzaj produktu',
               'attr' => ['id' =>'type-selector'],
                'choices'  => [
                    'Ołówek' => 'pencil',
                    'Długopis' => 'pen',
                ],
            ])
            ->add('color', ChoiceType::class,[
                'row_attr' => ['id'=>'color-selector'],
                'required' => false,
                'label' => 'Kolor długopisu',
                'choices' =>[
                    'Czerwony' =>'Red',
                    'Czarny'=>'Black',
                    'Niebieski' =>'Blue'
                ]
            ]  )
            ->add('amount', IntegerType::class, [
                'label' => 'Liczba produktów'
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Data::class,
        ]);
    }
}
