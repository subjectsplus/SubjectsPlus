<?php

namespace App\Form;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', CKEditorType::class)
            ->add('answer', CKEditorType::class)
            ->add('keywords', TextType::class, [
                'required' => false,
                'getter' => function(Faq $faq, FormInterface $form) { 
                    return $this->keywordsGetter($faq, $form);
                },
                'setter' => function(Faq $faq, ?string $keywords, FormInterface $form) {
                    $this->keywordsSetter($faq, $keywords, $form);
                },
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
                "empty_data" => new ArrayCollection(),
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
                "empty_data" => new ArrayCollection(),
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

    public function keywordsGetter(Faq $faq, FormInterface $form) {
        $keywords = $faq->getKeywords();
        if ($keywords) {
            return implode(',', $keywords);
        }
        return '';
    }

    public function keywordsSetter(Faq $faq, ?string $keywords, FormInterface $form) {
        // When keywords string is empty, an empty array is used for keywordsArray
        $keywordsArray = empty(trim($keywords)) ? [] : array_map('trim', explode(',', $keywords));
        $currentKeywords = $faq->getKeywords();
        $diffAdded = array_diff($keywordsArray, $currentKeywords); // keywords added
        $diffRemoved = array_diff($currentKeywords, $keywordsArray); // keywords removed
        
        // Add keywords
        foreach ($diffAdded as $keyword) {
            $faq->addKeyword($keyword);
        }

        // Remove keywords
        foreach ($diffRemoved as $keyword) {
            $faq->removeKeyword($keyword);
        }
    }
}
