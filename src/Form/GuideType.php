<?php

namespace App\Form;

use App\Entity\Subject;
use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\StaffRepository;

class GuideType extends AbstractType
{
    /**
     * @var staffRepository;
     */
    private $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ])
            ->add('staff', EntityType::class, [
                'choices' => $this->staffRepository->findBy(['active' => true]),
                'class' => Staff::class,
                'required' => true,
                'mapped' => false,
                'choice_label' => function(Staff $staff) {
                    return $staff->getFname() . ' ' . $staff->getLname();
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
            'data_class' => Subject::class,
        ]);
    }
}
