<?php

namespace App\Form;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', CKEditorType::class)
            ->add('answer', CKEditorType::class)
            ->add('keywords', null, [
                'required' => false,
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
                "empty_data" => [],
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
                "empty_data" => [],
                'placeholder' => 'Select a collection',
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faq::class,
        ]);
    }
}
