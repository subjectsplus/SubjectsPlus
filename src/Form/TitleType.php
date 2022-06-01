<?php

namespace App\Form;

use App\Entity\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\LocationType;

class TitleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title')
            ->add('alternateTitle')
            ->add('description')
            ->add('internalNotes')
            ->add('pre')
            ->add('lastModifiedBy')
            ->add('lastModified')
            ->add('location', CollectionType::class, [
                'entry_type' => LocationType::class,
                'entry_options' => ['label' => true],
                'allow_add' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Title::class,
        ]);
    }
}
