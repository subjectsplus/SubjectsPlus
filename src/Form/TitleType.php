<?php

namespace App\Form;

use App\Entity\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TitleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('alternateTitle')
            ->add('location', CollectionType::class, [
                'entry_type' => LocationType::class,
                'entry_options' => [
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            ])
            ->add('description')
            ->add('internalNotes')
            ->add('pre')
            ->add('lastModifiedBy')
            ->add('lastModified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Title::class,
        ]);
    }
}
