<?php

namespace App\Form;

use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('alternate_title')
            ->add('description')
            ->add('internal_notes')
            ->add('pre')
            ->add('location')
            ->add('call_number')
            ->add('eres_display')
            ->add('display_note')
            ->add('trial_start')
            ->add('trial_end')
            ->add('record_status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}
