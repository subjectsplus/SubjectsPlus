<?php

namespace App\Form;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: autosave ckeditor
        // TODO: flash messages
        $builder
            ->add('question', CKEditorType::class, [
                'required' => true,
            ])
            ->add('answer', CKEditorType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->add('keywords', TextType::class, [
                'required' => false,
                'mapped' => false,
                'empty_data' => '',
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => function($subject) {
                    return $subject;
                },
                'multiple' => true,
                'placeholder' => 'Select a subject',
                'expanded' => true,
            ])
            ->add('faqpage', EntityType::class, [
                'class' => Faqpage::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => function($faqPage) {
                    return $faqPage;
                },
                'multiple' => true,
                'placeholder' => 'Select a collection',
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faq::class,
            'validation_groups' => ['Default'],
        ]);
    }
}
