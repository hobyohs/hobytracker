<?php

namespace App\Form;

use App\Entity\AmbassadorEvaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AmbassadorEvaluationFormType extends AbstractType
{
    private function likertChoices(): array
    {
        return [
            'Strongly Disagree' => '1',
            'Disagree'          => '2',
            'Neutral'           => '3',
            'Agree'             => '4',
            'Strongly Agree'    => '5',
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $likert = [
            'expanded'    => false,
            'multiple'    => false,
            'required'    => false,
            'placeholder' => '',
            'choices'     => $this->likertChoices(),
        ];

        $builder
            ->add('evalEngaged', ChoiceType::class, array_merge($likert, [
                'label' => 'This ambassador was engaged and added thoughtful input to our group conversations.',
            ]))
            ->add('evalService', ChoiceType::class, array_merge($likert, [
                'label' => 'This ambassador is committed to community service and will likely complete 100 hours of volunteerism.',
            ]))
            ->add('evalRecommendation', ChoiceType::class, [
                'label'       => 'We recommend this ambassador to return on staff.',
                'expanded'    => false,
                'multiple'    => false,
                'required'    => false,
                'placeholder' => '',
                'choices'     => ['Yes' => '1', 'No' => '0'],
            ])
            ->add('evalPros', TextareaType::class, [
                'label'    => 'What specific qualities does this ambassador exhibit that would make them a good facilitator?',
                'required' => false,
            ])
            ->add('evalCons', TextareaType::class, [
                'label'    => 'What obstacles would this ambassador encounter in transitioning to a staff role?',
                'required' => false,
            ])
            ->add('evalComments', TextareaType::class, [
                'label'    => 'Please share any other comments about this ambassador.',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AmbassadorEvaluation::class,
        ]);
    }
}
