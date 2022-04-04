<?php

namespace App\Form;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\Faqpage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Repository\SubjectRepository;
use App\Repository\FaqpageRepository;


class FaqType extends AbstractType
{
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;

    /**
     * @var faqpageRepository;
     */
    private $faqpageRepository;

    public function __construct(SubjectRepository $subjectRepository, FaqpageRepository $faqpageRepository)
    {
        $this->subjectRepository = $subjectRepository;
        $this->faqpageRepository = $faqpageRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: autosave ckeditor
        // TODO: flash messages
        $builder
            ->add('question', TextareaType::class, [
                'required' => true,
            ])
            ->add('answer', CKEditorType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('keywords', TextType::class, [
                'required' => false,
                'mapped' => false,
                'empty_data' => '',
            ])
            ->add('subject', EntityType::class, [
                'choices' => $this->subjectRepository->findAllSubjectsAlphabetical(),
                'class' => Subject::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => function($subject) {
                    return $subject;
                },
                'multiple' => true,
                'placeholder' => 'Search and add',
                'attr' => ['data-placeholder-text' => 'Search and add'],
                'expanded' => false,
            ])
            ->add('faqpage', EntityType::class, [
                'choices' => $this->faqpageRepository->getAllFaqCollectionsAlpha(),
                'class' => Faqpage::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => function($faqPage) {
                    return $faqPage;
                },
                'multiple' => true,
                'placeholder' => 'Search and add',
                'attr' => ['data-placeholder-text' => 'Search and add'],
                'expanded' => false,
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
