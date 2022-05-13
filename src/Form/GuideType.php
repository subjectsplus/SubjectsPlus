<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Validation
        $builder
            ->add('subject', null, [
                'required' => true,
            ])
            ->add('shortform', null, [
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Subject' => 'Subject', 
                    'Topic' => 'Topic', 
                    'Course' => 'Course',
                    'Placeholder' => 'Placeholder',
                    // TODO: Ability to custom add more choices
                ],
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'Inactive' => 0,
                    'Active' => 1,
                    'Suppressed' => 2,
                ],
            ])
            ->add('keywords', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
