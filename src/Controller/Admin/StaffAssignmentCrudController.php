<?php

namespace App\Controller\Admin;

use App\Entity\StaffAssignment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class StaffAssignmentCrudController extends AbstractCrudController
{
    use SeminarYearChoiceTrait;

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return StaffAssignment::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->addBatchAction(Action::new('bg_check_complete', 'Mark as Cleared BG Check')
                ->linkToCrudAction('bgCheckComplete')
                ->addCssClass('btn btn-success')
                ->setIcon('fa-solid fa-person-circle-check'))
        ;
    }

    public function bgCheckComplete(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        foreach ($batchActionDto->getEntityIds() as $id) {
            $sa = $entityManager->find($className, $id);
            $sa->setBgCheckSubmitted(true);
            $sa->setBgCheckComplete(true);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Successfully marked ' . count($batchActionDto->getEntityIds()) . ' volunteers as having cleared their background checks.');
        $url = $this->adminUrlGenerator
            ->setController(StaffAssignmentCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Staff Assignment')
            ->setEntityLabelInPlural('Staff Assignments')
            ->setPaginatorPageSize(250)
            ->setDefaultSort(['user.lastName' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Staff Info');
        yield IdField::new('id')->onlyOnIndex();
        yield AssociationField::new('user')->setColumns(6);
        yield Field::new('consolidatedFirstName', 'Name')->onlyOnIndex();
        yield $this->seminarYearField()->setColumns(3);
        yield ChoiceField::new('status')->setColumns(3)->setChoices([
            'Active' => 'active',
            'Dropped' => 'dropped',
        ]);
        yield Field::new('position')->setColumns(6);
        yield ChoiceField::new('roleGroup', 'Role Group')->setColumns(6)->setChoices(array_combine(
            \App\Entity\StaffAssignment::ROLE_GROUPS,
            \App\Entity\StaffAssignment::ROLE_GROUPS,
        ))->setHelp('Auto-populated from Position on save if blank. Senior Facilitator, Team HQ, and Medical are treated as 21+ and get Seminar Ops access.')->setRequired(false);
        yield Field::new('age')->hideOnIndex()->setColumns(6)->setHelp('Age as of the seminar. Used to determine permissions.');
        yield AvatarField::new('photo')->hideOnIndex()->setColumns(6);
        yield ChoiceField::new('shirtSize')->hideOnIndex()->setChoices([
            'Small' => 'S',
            'Medium' => 'M',
            'Large' => 'L',
            'X-Large' => 'XL',
            'XX-Large' => 'XXL',
            'XXX-Large' => 'XXXL',
        ]);

        yield FormField::addTab('Group & Room');
        yield AssociationField::new('letterGroup')->setRequired(false)->setColumns(6);
        yield AssociationField::new('dormRoom')->setRequired(false)->setColumns(6);
        yield FormField::addPanel('Duty Assignments');
        yield Field::new('assignmentCheckIn', 'Check In Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentCheckInNotes', 'Check In Notes')->hideOnIndex()->setColumns(6);
        yield Field::new('assignmentClosingCeremonies', 'Closing Ceremony Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentClosingCeremoniesNotes', 'Closing Ceremony Notes')->hideOnIndex()->setColumns(6);
        yield Field::new('assignmentCheckOut', 'Check Out Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentCheckOutNotes', 'Check Out Notes')->hideOnIndex()->setColumns(6);

        yield FormField::addTab('Requirements');
        yield BooleanField::new('paperworkComplete', 'PSMs Received')->hideOnIndex();
        yield BooleanField::new('bgCheckSubmitted', 'Background Check Form Submitted')->hideOnIndex();
        yield BooleanField::new('bgCheckComplete', 'Background Check Cleared')->hideOnIndex();
        yield BooleanField::new('hobyAppComplete', 'HOBY.org App Complete')->hideOnIndex();
        yield BooleanField::new('hoursComplete', 'Hours Complete')->hideOnIndex();
        yield BooleanField::new('ambRegistered', 'Ambassador Registered')->hideOnIndex();
        yield BooleanField::new('fundraisingComplete', 'Fundraising Complete')->hideOnIndex();
        yield TextareaField::new('requirementNotes', 'Notes')->hideOnIndex();

        yield FormField::addTab('Health & Medical');
        yield TextareaField::new('currentConditions')->hideOnIndex();
        yield TextareaField::new('exerciseLimits')->hideOnIndex();
        yield TextareaField::new('dietRestrictions')->hideOnIndex();
        yield TextareaField::new('dietInfo')->hideOnIndex();
        yield Field::new('dietSeverity', 'Diet Restriction Severity')->hideOnIndex();
        yield TextareaField::new('allergies', 'Other Allergies')->hideOnIndex();
        yield TextareaField::new('medAllergies', 'Drug Allergies')->hideOnIndex();
        yield TextareaField::new('currentRx', 'Current Prescriptions')->hideOnIndex();

        yield FormField::addTab('Emergency Contact');
        yield Field::new('ecFirstName', 'First Name')->hideOnIndex()->setColumns(6);
        yield Field::new('ecLastName', 'Last Name')->hideOnIndex()->setColumns(6);
        yield Field::new('ecRelationship', 'Relationship')->hideOnIndex();
        yield Field::new('ecPhone1', 'Phone 1')->hideOnIndex()->setColumns(6);
        yield Field::new('ecPhone2', 'Phone 2')->hideOnIndex()->setColumns(6);

        // Evaluation tab removed — eval data now lives in the StaffEvaluation
        // entity and is managed via "Staff Evaluations" in the admin sidebar.
    }
}
