<?php

namespace App\Form;

use App\Entity\User;
use App\Service\JsonToStringTransformerService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EditUserType extends AbstractType
{
    public function __construct(
        private JsonToStringTransformerService $transformer,
    ){

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => 'disabled',
                    'minlength' => '2',
                    'maxlength' => '180',
                ],
                'label' => 'Username',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 180]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Last name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('firstName' , TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'First name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '180',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 180]),
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '18',
                    'max' => '110'

                ],
                'label' => 'Age',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Range(
                        notInRangeMessage: 'You must be at least 18 years old.',
                        min: 18,
                        max: 110
                    )]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female'
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Gender',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('sexuality', ChoiceType::class, [
                'choices' => [
                    'Hetero' => 'hetero',
                    'Homo' => 'homo',
                    'Bi' => 'bi'
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Sexuality',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('tags', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '300',
                ],
                'label' => 'Tags',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'help' => 'Each tag must be separate with a coma(exemple1,exemple2,exemple2)',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 180]),
                ],

            ])
            ->add('biography', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '4'
                ],
                'label' => 'Biography',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(
                        max: 300,
                        maxMessage: 'Your biography must contains 300 characters max.'
                    )]
            ])
            ->add('Edit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ])
            ->get('tags')
            ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
