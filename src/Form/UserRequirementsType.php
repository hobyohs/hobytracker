<?php

namespace App\Form;

use App\Entity\StaffAssignment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserRequirementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paperworkComplete', CheckboxType::class, ['label' => 'Formstack Submitted', 'required' => false])
            ->add('bgCheckSubmitted', CheckboxType::class, ['label' => 'Background Check Form Submitted', 'required' => false])
            ->add('bgCheckComplete', CheckboxType::class, ['label' => 'Background Check Cleared', 'required' => false])
            ->add('hobyAppComplete', CheckboxType::class, ['label' => 'Applied at HOBY.org', 'required' => false])
            ->add('hoursComplete', CheckboxType::class, ['label' => 'Volunteer Hours Complete', 'required' => false])
            ->add('ambRegistered', CheckboxType::class, ['label' => 'Ambassador Registered (or good faith effort made)', 'required' => false])
            ->add('fundraisingComplete', CheckboxType::class, ['label' => 'Fundraising Requirement Met', 'required' => false])
            ->add('requirementNotes', TextareaType::class, ['label' => 'Notes', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StaffAssignment::class,
        ]);
    }
}
