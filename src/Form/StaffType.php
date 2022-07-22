<?php

namespace App\Form;

use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('staffPhoto', FileType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('lname')
            ->add('fname')
            ->add('title')
            ->add('tel')
            ->add('department')
            ->add('email')
            ->add('active')
            ->add('ptags')
            ->add('bio')
            ->add('password')
            //->add('supervisor')
            ->add('emergencyContactName')
            ->add('emergencyContactRelation')
            ->add('emergencyContactPhone')
            ->add('streetAddress')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('homePhone')
            ->add('cellPhone')
            ->add('fax')
            //->add('roles')
            ->add('userType')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
