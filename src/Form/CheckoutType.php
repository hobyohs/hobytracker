<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CheckoutType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('checkedOut', HiddenType::class, array (
				'data' => 1
			))
			->add('checkout_deposit_decision', ChoiceType::class, array (
				'choices' => array (
					'Movie' => 'movie',
					'Return' => 'return',
					'Lost Keys' => 'lost'
				),
				'placeholder' => 'Select...'
			))
			->add('save', SubmitType::class, array (
				'label' => 'Check Out',
				'attr' => ['class' => 'checkin-button btn-primary']
			))
		;
	}


}
