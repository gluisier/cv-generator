<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class DurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('what')
            ->add('startDate', BirthdayType::class)
            ->add('endDate', BirthdayType::class)
            ->add('company', EntityType::class, array(
                'class' => \App\Entity\Company::class,
                'choice_label' => 'name',
            ))
        ;
    }
}
