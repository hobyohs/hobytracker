<?php

namespace App\Controller\Admin;

use App\Entity\LetterGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LetterGroupRepository;

class LetterGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LetterGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Group')
            ->setEntityLabelInPlural('Groups')
        ;

        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'letter',
            'color',
            'homeBuilding',
            'homeRoom',
            'interview_assignment',
            AssociationField::new('facilitators')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true
                ]),
            AssociationField::new('ambassadors')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true
                ])
        ];
    }

    #[Route('/admin/group_demo_report', name: 'admin_group_demo_report')]
    public function groupDemoReportAction(LetterGroupRepository $letterGroupRepository): Response
    {
        $groups = $letterGroupRepository->pullDemoSummary();
        return $this->render('bundles/EasyAdminBundle/groupDemoReport.html.twig', array(
            'groups' => $groups
        ));
    }
    
}
