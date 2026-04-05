<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CheckinType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('checkedIn', HiddenType::class, array (
				'data' => 1
			))
			->add('save', SubmitType::class, array (
				'label' => 'Check In',
				'attr' => ['class' => 'checkin-button btn-primary']
			))
		;
	}


}
