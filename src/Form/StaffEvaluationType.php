<?php

namespace App\Form;

use App\Entity\StaffAssignment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StaffEvaluationType extends AbstractType
{
    private function likertChoices(): array
    {
        return [
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $likert = ['attr' => ['class' => 'show-bars'], 'placeholder' => 'Select...', 'choices' => $this->likertChoices()];

        $builder
            ->add('evalPros', TextareaType::class, [
                'label' => "What were this facilitator's greatest strengths?",
            ])
            ->add('evalCons', TextareaType::class, [
                'label' => "What obstacles, challenges, or weaknesses did this facilitator face? Did they grow and address them, or do the challenges remain?",
            ])
            ->add('evalDiscussions', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was effective in helping the group start discussions.",
            ]))
            ->add('evalEnthusiastic', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was enthusiastic and energetic throughout the seminar.",
            ]))
            ->add('evalOrganized', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was personally organized and helped keep you and the rest of the group on track.",
            ]))
            ->add('evalEqually', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator treated each ambassador equally and didn't play favorites.",
            ]))
            ->add('evalResponsible', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was responsible and effective in protecting the group's physical and emotional well-being.",
            ]))
            ->add('evalAttentive', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was attentive and interested during all speakers, panels, and activities. They did not step out frequently or use their phone for non-HOBY purposes in front of the group.",
            ]))
            ->add('evalInclude', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator actively worked to include all ambassadors in group discussions and activities.",
            ]))
            ->add('evalProfessional', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator acted professionally and maturely throughout the seminar. They were an excellent representative of HOBY to our host, partners, and parents.",
            ]))
            ->add('evalPunctual', ChoiceType::class, array_merge($likert, [
                'label' => "This facilitator was punctual throughout the seminar both to group activities and staff meetings. You or the group never waited for them to arrive.",
            ]))
            ->add('evalWhynot', TextareaType::class, [
                'label' => "Is there any reason this facilitator should not be invited back next year? Why?",
                'required' => false,
            ])
            ->add('evalComments', TextareaType::class, [
                'label' => "Please share anything else you'd like us to know about this facilitator.",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StaffAssignment::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_staffevaluation';
    }
}
