<?php

namespace App\Form;

use App\Entity\ComingsAndGoings;
use App\Entity\Ambassador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ComingsAndGoingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('departure', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('arrival', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('ambassador', EntityType::class, array (
            'class' => Ambassador::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->orderBy('a.lastName', 'ASC');
            },
            'placeholder' => 'Select...',
            'choice_label' => 'getAlphaDisplayName'
        ))
        ->add('notes');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComingsAndGoings::class,
        ]);
    }
}
