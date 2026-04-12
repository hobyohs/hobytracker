<?php

namespace App\Controller\Admin;

use App\Entity\StaffEvaluation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class StaffEvaluationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StaffEvaluation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Staff Evaluation')
            ->setEntityLabelInPlural('Staff Evaluations')
            ->setDefaultSort(['seminarYear' => 'DESC', 'subject' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $ratingChoices = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];

        yield IdField::new('id')->onlyOnDetail();
        yield AssociationField::new('subject', 'Staff Member (Subject)');
        yield AssociationField::new('evaluator', 'Evaluator')->setRequired(false);
        yield IntegerField::new('seminarYear', 'Year');
        yield ChoiceField::new('status')
            ->setChoices(['Draft' => 'draft', 'Submitted' => 'submitted']);
        yield DateTimeField::new('submittedAt', 'Submitted At')->hideOnIndex();
        yield ChoiceField::new('evalDiscussions', 'Led Discussions')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalEnthusiastic', 'Enthusiastic')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalOrganized', 'Organized')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalEqually', 'Engaged Equally')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalResponsible', 'Responsible')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalAttentive', 'Attentive')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalInclude', 'Inclusive')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalProfessional', 'Professional')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalPunctual', 'Punctual')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield TextareaField::new('evalPros', 'Strengths')->hideOnIndex();
        yield TextareaField::new('evalCons', 'Areas for Growth')->hideOnIndex();
        yield TextareaField::new('evalWhynot', 'Why Not Return')->hideOnIndex();
        yield TextareaField::new('evalComments', 'Comments')->hideOnIndex();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('subject'))
            ->add(EntityFilter::new('evaluator'))
            ->add(ChoiceFilter::new('status')->setChoices(['Draft' => 'draft', 'Submitted' => 'submitted']));
    }
}
