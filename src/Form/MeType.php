<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('fullName')
            ->add('nationality', TextType::class, array(
                'required'      => true,
            ))
            ->add('maritalStatus')
            ->add('birthDate', BirthdayType::class, array(
                'required'      => true,
            ))
            ->add('email', EmailType::class, array(
                'required'      => true,
            ))
            ->add('phone', TextType::class, array(
                'required'      => true,
            ))
            ->add('summary')
            ->add('skills', CollectionType::class, array(
                'entry_type'=> SkillType::class,
                'prototype' => true,
                'allow_add' => true,
            ))
            ->add('languages', CollectionType::class, array(
                'entry_type'=> LanguageType::class,
                'prototype' => true,
                'allow_add' => true,
            ))
            ->add('experiences', CollectionType::class, array(
                'entry_type'=> ExperienceType::class,
                'prototype' => true,
                'allow_add' => true,
            ))
            ->add('trainings', CollectionType::class, array(
                'entry_type'=> TrainingType::class,
                'prototype' => true,
                'allow_add' => true,
            ))
            ->add('references', EntityType::class, array(
                'class'     => Person::class,
                'required'  => false,
                'multiple'  => true,
            ))
            ->add('hobbies', CollectionType::class, array(
                'entry_type'=> HobbyType::class,
                'prototype' => true,
                'allow_add' => true,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Person::class
        ));
    }
}
