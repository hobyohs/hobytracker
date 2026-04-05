<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;

class UserRequirementsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paperworkComplete', CheckboxType::class, array('label' => 'Formstack Submitted', 'required' => false))
            ->add('bgCheckSubmitted', CheckboxType::class, array('label' => 'Background Check Form Submitted', 'required' => false))
            ->add('bgCheckComplete', CheckboxType::class, array('label' => 'Background Check Cleared', 'required' => false))
            ->add('hobyAppComplete', CheckboxType::class, array('label' => 'Applied at HOBY.org', 'required' => false))
            ->add('hoursComplete', CheckboxType::class, array('label' => 'Volunteer Hours Complete', 'required' => false))
            ->add('ambRegistered', CheckboxType::class, array('label' => 'Ambassador Registered (or good faith effort made)', 'required' => false))
            // ->add('planningComplete', CheckboxType::class, array('label' => 'Committee Assignment Completed', 'required' => false))
            ->add('fundraisingComplete', CheckboxType::class, array('label' => 'Fundraising Requirement Met', 'required' => false))
            ->add('requirementNotes', TextareaType::class, array('label' => 'Notes', 'required' => false))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

}
