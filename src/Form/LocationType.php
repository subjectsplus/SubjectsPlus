<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('callNumber')
            ->add('location')
            ->add('eresDisplay')
            ->add('displayNote')
            ->add('helpguide')
            ->add('citationGuide')
            ->add('ctags')
            ->add('recordStatus')
            ->add('trialStart')
            ->add('trialEnd')
            ->add('format')
            ->add('accessRestrictions')
            ->add('title')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
