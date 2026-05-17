<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'label'=>'Login',
                'row_attr' => ['class'=>'column-wrapper'],

            ] )
            ->add('firstName', TextType::class, [
                'label'=>'Imię',
                'row_attr' => ['class'=>'column-wrapper'],
            ] )
            ->add('lastName', TextType::class, [
                'label'=>'Nazwisko',
                'row_attr' => ['class'=>'column-wrapper'],
            ] )                    
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Hasło',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'row_attr' => ['class'=>'column-wrapper'],
                'constraints' => [
                    new NotBlank(
                        message: 'Wprowadź hasło',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Hasło musi mieć min. 6 znaków',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
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
