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

class ApplicantType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('reviewer1_rating', ChoiceType::class, [
				'label' => "Katie Rating",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strong No Hire' => 1,
					'No Hire' => 2,
					'Hire' => 3,
					'Strong Hire' => 4,
				],
				'required' => false,
			])
			->add('reviewer1_notes', TextareaType::class, [
				'label' => "Katie Notes",
				'required' => false,
			])
			->add('reviewer2_rating', ChoiceType::class, [
				'label' => "Jacob Rating",
				'attr' => ['class' => 'show-bars'],
				'placeholder' => "Select...",
				'choices'  => [
					'Strong No Hire' => 1,
					'No Hire' => 2,
					'Hire' => 3,
					'Strong Hire' => 4,
				],
				'required' => false,
			])
			->add('reviewer2_notes', TextareaType::class, [
				'label' => "Jacob Notes",
				'required' => false,
			])
			->add('decision', ChoiceType::class, [
				'label' => "Decision",
				'placeholder' => "Select...",
				'choices'  => [
					'Facilitator' => 'Facilitator',
					'J-Crew' => 'J-Crew',
					'Team HQ' => 'Team HQ',
					'Tentative' => 'Tentative',
					'No Hire' => 'No Hire',
				],
				'required' => false,
			])
			;
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Applicant'
		));
	}

	public function getBlockPrefix()
	{
		return 'app_applicant';
	}


}
