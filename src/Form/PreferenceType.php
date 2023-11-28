<?php

namespace App\Form;

use App\Entity\Gender;
use App\Entity\Preferences;
use App\Entity\Sexuality;
use App\Entity\Tag;
use App\Repository\GenderRepository;
use App\Repository\SexualityRepository;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PreferenceType extends AbstractType
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
        $preferences = $options['data'] ?? null;

        $builder
            ->add('min_age' ,IntegerType::class, [
        'attr' => [
            'class' => 'form-control',
            'min' => '18',
            'max' => '99'

        ],
        'label' => 'Min age',
        'label_attr' => [
            'class' => 'form-label matchaRose mt-4'
        ],
        'constraints' => [
            new Assert\Range(
                notInRangeMessage: 'You must choose an age between 18 and 99.',
                min: 18,
                max: 99
            )]
    ])
            ->add('max_age',IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '18',
                    'max' => '99'

                ],
                'label' => 'Max age',
                'label_attr' => [
                    'class' => 'form-label matchaRose mt-4'
                ],
                'constraints' => [
                    new Assert\Range(
                        notInRangeMessage: 'You must choose an age between 18 and 99.',
                        min: 18,
                        max: 99
                    )]
            ])
            ->add('sexualities',EntityType::class, [
                'class' => Sexuality::class,
                'choice_label' => 'name',
                'choices'=> $this->sexualityRepository->findAll(),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Select Your Preferred Sexualities',
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
                'attr' =>[
                    'class' => 'form-check-inline'
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input'];
                },
                'data'=>  $preferences && $preferences->getSexualities() ? $preferences->getSexualities() : ['']
            ])
            ->add('genders',EntityType::class, [
        'class' => Gender::class,
        'choice_label' => 'name',
        'choices'=> $this->genderRepository->findAll(),
        'expanded' => true,
        'multiple' => true,
        'label' => 'Select Your Preferred Genders',
        'label_attr' => [
            'class' => 'form-check-label mt-4'
        ],
        'attr' =>[
            'class' => 'form-check-inline'
        ],
        'choice_attr' => function ($choice, $key, $value) {
            return ['class' => 'form-check-input'];
        },
        'data'=>  $preferences && $preferences->getGenders() ? $preferences->getGenders() : ['']
    ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'choices'=> $this->tagRepository->findAll(),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Select Your Interests',
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
                'attr' =>[
                    'class' => 'form-check-inline'
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input'];
                },
                'data'=> $preferences && $preferences->getTags() ? $preferences->getTags() : ['']
            ])
        ->add('Save', SubmitType::class, [
        'attr' => [
            'class' => 'btn btn-primary mt-4'
        ]
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Preferences::class,
        ]);
    }
}
