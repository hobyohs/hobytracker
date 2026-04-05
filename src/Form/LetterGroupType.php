<?php

namespace App\Form;

use App\Entity\LetterGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LetterGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('letter')
            ->add('homeBuilding')
            ->add('homeRoom')
            ->add('color')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LetterGroup::class,
        ]);
    }
}
