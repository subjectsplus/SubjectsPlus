<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Validation
        $builder
            ->add('subject', null, [
                'label' => 'Guide Title',
            ])
            ->add('shortform', null, [
                'label' => 'Shortform',
                // TODO: Slug creation unique for shortform
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Subject' => 'Subject', 
                    'Topic' => 'Topic', 
                    'Course' => 'Course',
                    'Placeholder' => 'Placeholder',
                    // TODO: Ability to custom add more choices
                ],
                'label' => 'Type',
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'Inactive' => 0,
                    'Active' => 1,
                ],
                'label' => 'Visibility',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
