<?php

namespace App\Form;

use App\Entity\Ambassador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmbassadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('photo')
            ->add('prefName')
            ->add('ethnicity')
            ->add('pronouns')
            ->add('county')
            ->add('homePhone')
            ->add('cellPhone')
            ->add('email')
            ->add('school')
            ->add('shirtSize')
            ->add('parent1FirstName')
            ->add('parent1LastName')
            ->add('parent1Phone1')
            ->add('parent1Phone2')
            ->add('parent1Email')
            ->add('parent2FirstName')
            ->add('parent2LastName')
            ->add('parent2Phone1')
            ->add('parent2Phone2')
            ->add('parent2Email')
            ->add('dorm')
            ->add('room')
            ->add('checkedIn')
            ->add('checkedOut')
            ->add('checkout_deposit_decision')
            ->add('checkin_paperwork')
            ->add('checkin_deposit')
            ->add('checkin_deposit_method')
            ->add('checkin_deposit_notes')
            ->add('checkin_meds')
            ->add('cg_form')
            ->add('psms_uploaded_on')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ambassador::class,
        ]);
    }
}
