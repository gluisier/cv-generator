<?php

namespace App\Form;

use App\Form\DurationType as AbstractType;
use App\Form\RealisationType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('genericSummary', TextareaType::class)
            ->add('fixedTerm')
            ->add('realisations', CollectionType::class, array(
                'entry_type'   => RealisationType::class,
                'required'     => false,
                'prototype'    => true,
                'allow_add'    => true,
                'by_reference' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Experience::class,
        ));
    }
}
