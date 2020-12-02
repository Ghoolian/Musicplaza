<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Prefer not to say' => 'Prefer not to say'
                ]])
            ->add('birthday', DateType::class, [
                'widget' => 'choice',
                'years' => range(1950,2015),
            ])
            ->add('profilepicture', FileType::class, [
                'label' => 'Profile picture',

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => 'Please upload a valid image',
                        'maxWidth' => '1920',
                        'maxHeight' => '1080',
                    ])
                ],
            ])

            ->add('playstation', TextType::class, ['required' => false])
            ->add('nintendo', TextType::class, ['required' => false])
            ->add('xbox', TextType::class, ['required' => false])
            ->add('twitter', TextType::class, ['required' => false])
            ->add('discord', TextType::class, ['required' => false])
            ->add('facebook', TextType::class, ['required' => false])
            ->add('password', PasswordType::class)
            ->add('password_verify', PasswordType::class, [
                'mapped' => false
            ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}