<?php

namespace App\Form;

use App\Entity\Media;
use App\Service\MediaService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title')
        ->add('file', FileType::class, [
            'required' => true,
            'label' => 'Select File',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'validation_groups' => [
                MediaService::class,
                'determineValidationGroups',
            ]
        ]);
    }
}
