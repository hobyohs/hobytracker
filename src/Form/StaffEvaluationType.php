<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class StaffEvaluationType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('eval_pros', TextareaType::class, [
				'label' => "What were this facilitator's greatest strengths?"
			])
			->add('eval_cons', TextareaType::class, [
				'label' => "What obstacles, challenges, or weaknesses did this facilitator face? Did they grow and address them, or do the challenges remain?"
			])
			->add('eval_discussions', ChoiceType::class, [
				'label' => "This facilitator was effective in helping the group start discussions.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_enthusiastic', ChoiceType::class, [
				'label' => "This facilitator was enthusiastic and energetic throughout the seminar.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_organized', ChoiceType::class, [
				'label' => "This facilitator was personally organized and helped keep you and the rest of the group on track.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_equally', ChoiceType::class, [
				'label' => "This facilitator treated each ambassador equally and didn't play favorites.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_responsible', ChoiceType::class, [
				'label' => "This facilitator was responsible and effective in protecting the group's physical and emotional well-being.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_attentive', ChoiceType::class, [
				'label' => "This facilitator was attentive and interested during all speakers, panels, and activities. They did not step out frequently or use their phone for non-HOBY purposes in front of the group.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_include', ChoiceType::class, [
				'label' => "This facilitator actively worked to include all ambassadors in group discussions and activities.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_professional', ChoiceType::class, [
				'label' => "This facilitator acted professionally and maturely throughout the seminar. They were an excellent representative of HOBY to our host, partners, and parents.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
		->add('eval_punctual', ChoiceType::class, [
				'label' => "This facilitator was punctual throughout the seminar both to group activities and staff meetings. You or the group never waited for them to arrive.",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strongly Disagree' => 1,
					'Disagree' => 2,
					'Neutral' => 3,
					'Agree' => 4,
					'Strongly Agree' => 5,
				],
			])
			->add('eval_whynot', TextareaType::class, [
				'label' => "Is there any reason this facilitator should not be invited back next year? Why?",
				'required' => false
			])
			->add('eval_comments', TextareaType::class, [
				'label' => "Please share anything else you'd like us to know about this facilitator.",
				'required' => false
			])
			;
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\User'
		));
	}

	public function getBlockPrefix()
	{
		return 'app_staffevaluation';
	}


}
