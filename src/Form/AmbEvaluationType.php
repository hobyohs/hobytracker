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

class AmbEvaluationType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('eval_engaged', ChoiceType::class, [
				'label' => "This ambassador was engaged and added thoughtful input to our group conversations.",
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
			->add('eval_service', ChoiceType::class, [
				'label' => "This ambassador is committed to community service and will likely complete 100 hours of volunteerism.",
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
			->add('eval_pros', TextareaType::class, [
				'label' => "What specific qualities does this ambassador exhibit that would make them a good facilitator?"
			])
			->add('eval_cons', TextareaType::class, [
				'label' => "What obstacles would this ambassador encounter in transitioning to a staff role?"
			])
			->add('eval_recommendation', ChoiceType::class, [
				'label' => "We recommend this ambassador to return on staff.",
				'placeholder' => "Select...",
				'choices'  => [
					'Yes' => 1,
					'No' => 0,
				],
			])
			->add('eval_comments', TextareaType::class, [
				'label' => "Please share any other comments about this ambassador.",
				'required' => false
			])
			;
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Ambassador'
		));
	}

	public function getBlockPrefix()
	{
		return 'app_ambevaluation';
	}


}
