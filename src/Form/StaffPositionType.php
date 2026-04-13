<?php

namespace App\Form;

use App\Entity\StaffAssignment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * A text input that behaves like an open-ended picker: admins get a browser-native
 * autocomplete dropdown with common HOBY staff positions, and can either pick one of
 * the suggested values or type a novel custom title. Backed by HTML5 <datalist>, so
 * zero JS, zero dependencies, works on every modern browser including mobile.
 *
 * Suggestions come from StaffAssignment::getPositionChoices(), which is the single
 * source of truth for the canonical position list.
 */
class StaffPositionType extends AbstractType
{
    public function getParent(): string
    {
        return TextType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // The datalist id is stable so the form theme's <datalist> element can pair
        // with the input via its `list` attribute. Both ends live in the form theme
        // template `staff_position_widget`.
        $view->vars['attr']['list']    = 'staff-position-options';
        $view->vars['position_choices'] = StaffAssignment::getPositionChoices();
    }

    public function getBlockPrefix(): string
    {
        return 'staff_position';
    }
}
