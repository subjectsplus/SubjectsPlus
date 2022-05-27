<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('callNumber')
            ->add('eresDisplay')
            ->add('format', CollectionType::class, [
                'entry_type' => FormatType::class,
                'entry_options' => ['label' => false],
            ])
            ->add('displayNote')
            ->add('helpguide')
            ->add('citationGuide')
            ->add('ctags')
            ->add('recordStatus')
            ->add('trialStart')
            ->add('trialEnd')
            ->add('accessRestrictions', CollectionType::class, [
                'entry_type' => RestrictionType::class,
                'entry_options' => ['label' => false],
            ])
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
