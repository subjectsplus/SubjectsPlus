<?php

namespace App\Form;

use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lname')
            ->add('fname')
            ->add('title')
            ->add('tel')
            ->add('department')
            ->add('staffSort')
            ->add('email')
            ->add('ip')
            ->add('accessLevel')
            ->add('password')
            ->add('active')
            ->add('ptags')
            ->add('extra')
            ->add('bio')
            ->add('positionNumber')
            ->add('jobClassification')
            ->add('roomNumber')
            ->add('supervisorId')
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
            ->add('intercom')
            ->add('latLong')
            ->add('socialMedia')
            //->add('roles')
            ->add('isVerified')
            ->add('userType')
            ->add('subject')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
