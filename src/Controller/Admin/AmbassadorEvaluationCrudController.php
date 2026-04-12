<?php

namespace App\Controller\Admin;

use App\Entity\AmbassadorEvaluation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class AmbassadorEvaluationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AmbassadorEvaluation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ambassador Evaluation')
            ->setEntityLabelInPlural('Ambassador Evaluations')
            ->setDefaultSort(['seminarYear' => 'DESC', 'ambassador' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $ratingChoices = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];

        yield IdField::new('id')->onlyOnDetail();
        yield AssociationField::new('ambassador');
        yield IntegerField::new('seminarYear', 'Year');
        yield AssociationField::new('submittedBy', 'Submitted By')->setRequired(false);
        yield ChoiceField::new('status')
            ->setChoices(['Draft' => 'draft', 'Submitted' => 'submitted']);
        yield DateTimeField::new('submittedAt', 'Submitted At')->hideOnIndex();
        yield ChoiceField::new('evalEngaged', 'Engaged')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalService', 'Service')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield ChoiceField::new('evalRecommendation', 'Recommendation')->setChoices($ratingChoices)->setRequired(false)->hideOnIndex();
        yield TextareaField::new('evalPros', 'Strengths')->hideOnIndex();
        yield TextareaField::new('evalCons', 'Areas for Growth')->hideOnIndex();
        yield TextareaField::new('evalComments', 'Comments')->hideOnIndex();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('ambassador'))
            ->add(ChoiceFilter::new('status')->setChoices(['Draft' => 'draft', 'Submitted' => 'submitted']));
    }
}
