<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('level', 'choice', array(
                'choices' => array(
                    'app.language.level.0',
                    'app.language.level.1',
                    'app.language.level.2',
                    'app.language.level.3',
                    'app.language.level.4',
                    'app.language.level.5',
                    'app.language.level.6',
                    'app.language.level.7',
                    'app.language.level.8',
                )
            ))
            ->add('meaning')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Language::class,
        ));
    }
}
