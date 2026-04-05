<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CallType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('juniorCallMade', HiddenType::class, array (
				'data' => 1
			))
			->add('juniorCallDisposition', ChoiceType::class, [
				'label' => "What was the outcome of the call?",
				'placeholder' => "Select...",
				'choices'  => [
					'Spoke with ambassador' => 'spoke_with_ambassador',
					'Spoke with ambassador\'s family, will call back' => 'spoke_with_family',
					'Left a message, will call back' => 'left_message',
					'Wrong number' => 'wrong_number',
					'Other (please explain in notes)' => 'other',
				],
			])
			->add('juniorCallNotes', TextareaType::class, [
				'label' => "Let us know how the call went or any questions we need to follow up on."
			])
			->add('save', SubmitType::class, array (
				'label' => 'Call Complete',
				'attr' => ['class' => 'checkin-button btn-primary']
			))
		;
	}


}
