<?php

namespace App\Form;

use App\Entity\Gender;
use App\Entity\Sexuality;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\GenderRepository;
use App\Repository\SexualityRepository;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    private $genderRepository;
    private $sexualityRepository;

    private $tagRepository;
    public function __construct(
        GenderRepository $genderRepository,
        SexualityRepository $sexualityRepository,
        TagRepository $tagRepository
    ){
        $this->genderRepository = $genderRepository;
        $this->sexualityRepository = $sexualityRepository;
        $this->tagRepository = $tagRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
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
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'choices' => $this->genderRepository->findAll(),
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Gender',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'placeholder' => '---',
                'data' => $user ? $user->getGender() : null
            ])
            ->add('sexuality', EntityType::class, [
                'class' => Sexuality::class,
                'choice_label' => 'name',
                'choices' => $this->sexualityRepository->findAll(),
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Sexuality',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'placeholder' => '---',
                'data' => $user ? $user->getSexuality() : null
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'choices'=> $this->tagRepository->findAll(),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Tags',
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
                'attr' =>[
                    'class' => 'form-check-inline'
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input'];
                },
                'data'=> $user ? $user->getTags() : ['']
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
                'required' => false,
                'constraints' => [
                    new Assert\Length(
                        max: 300,
                        maxMessage: 'Your biography must contains 300 characters max.'
                    ),
                    ]
            ])
            ->add('Edit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
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

    private function getGenderChoices(){
        $genders = $this->genderRepository->findAll();
        $choices = [];
        foreach ($genders as $gender){
            $choices[$gender->getName()] = $gender->getId();
        }
        return $choices;
    }

    private function getSexualityChoices(){
        $sexualities = $this->sexualityRepository->findAll();
        $choices = [];
        foreach ($sexualities as $sexuality){
            $choices[$sexuality->getName()] = $sexuality->getId();
        }
        return $choices;
    }

    private function getTagChoices(){
        $tags = $this->tagRepository->findAll();
        $choices = [];
        foreach ($tags as $tag){
            $choices[$tag->getName()] = $tag->getId();
        }
        return $choices;
    }

    private function getUserTags($user){
        if ($user){
            $toto = $user->getTags() ? $user->getTags()->map(fn($tag) => $tag->getId())->toArray() : [''];
            return $toto;
        }
        return [''];
    }
}
