<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\TechnologyType;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('link')
            ->add('description', TextareaType::class)
            ->add('tasks', CollectionType::class, array(
                'entry_type'   => TextareaType::class,
                'allow_add'    => true,
                'prototype'    => true,
            ))
            ->add('technologies', CollectionType::class, array(
                'entry_type'   => TechnologyType::class,
                'allow_add'    => true,
                'prototype'    => true,
                'by_reference' => false,
            ))
            ->add('importance', IntegerType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Realisation::class,
        ));
    }
}
