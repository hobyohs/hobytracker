<?php

namespace App\Controller\Admin;

use App\Entity\Ambassador;
use App\Entity\DormRoom;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
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
use Doctrine\ORM\QueryBuilder;

class AmbassadorCrudController extends AbstractCrudController
{
    
    private $adminUrlGenerator;
    
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    
    public static function getEntityFqcn(): string
    {
        return Ambassador::class;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->addBatchAction(Action::new('paypal_deposits', 'Mark as Paid Deposit via PayPal')
                ->linkToCrudAction('paypalDeposits')
                ->addCssClass('btn btn-success')
                ->setIcon('fa-brands fa-paypal'))
        ;
    }
    
    public function paypalDeposits(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        foreach ($batchActionDto->getEntityIds() as $id) {
            $amb = $entityManager->find($className, $id);
            $amb->setCheckinDeposit(TRUE);
            $amb->setCheckinDepositMethod("PayPal");
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Successfully marked '.count($batchActionDto->getEntityIds()).' ambassadors as having paid their key deposits via PayPal.');
        $url = $this->adminUrlGenerator
        ->setController(AmbassadorCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();
        
        return $this->redirect($url);
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ambassador')
            ->setEntityLabelInPlural('Ambassadors')
            ->setPaginatorPageSize(250)
            ->setDefaultSort(['lastName' => 'ASC'])
        ;

    }

        public function configureFields(string $pageName): iterable
    {
            
            
        
            yield FormField::addTab('Ambassador Info');
            yield IdField::new('id')->onlyOnIndex();
            yield Field::new('consolidatedFirstName')->setColumns(6)->onlyOnIndex();
            yield Field::new('firstName')->setColumns(6)->hideOnIndex();
            yield Field::new('prefName', 'Preferred Name')->hideOnIndex()->setColumns(6);
            yield Field::new('lastName');
            yield AvatarField::new('photo')->hideOnIndex();
            yield Field::new('gender')->hideOnIndex()->setColumns(6);
            yield Field::new('pronouns')->hideOnIndex()->setColumns(6);
            yield Field::new('school')->setColumns(6);
            yield Field::new('county')->hideOnIndex()->setColumns(6);
            yield Field::new('ethnicity')->hideOnIndex()->setColumns(6);
            yield ChoiceField::new('shirtSize')->hideOnIndex()->setChoices([
                'Small' => 'S',
                'Medium' => 'M',
                'Large' => 'L',
                'X-Large' => 'XL',
                'XX-Large' => 'XXL',
                'XXX-Large' => 'XXXL',
                
            ])->setColumns(6);
            
            yield FormField::addTab('Family & Contact Info');
            yield FormField::addPanel('Ambassador');
            yield Field::new('cellPhone')->hideOnIndex()->setColumns(6);
            yield Field::new('email')->hideOnIndex()->setColumns(6);
            
            yield FormField::addPanel('Parent/Guardian 1');
            yield Field::new('parent1FirstName', 'First Name')->hideOnIndex()->setColumns(6);
            yield Field::new('parent1LastName', 'Last Name')->hideOnIndex()->setColumns(6);
            yield Field::new('parent1Phone1', 'Phone 1')->hideOnIndex()->setColumns(4);
            yield Field::new('parent1Phone2', 'Phone 2')->hideOnIndex()->setColumns(4);
            yield Field::new('parent1Email', 'Email')->hideOnIndex()->setColumns(4);
            
            yield FormField::addPanel('Parent/Guardian 2');
            yield Field::new('parent2FirstName', 'First Name')->hideOnIndex()->setColumns(6);
            yield Field::new('parent2LastName', 'Last Name')->hideOnIndex()->setColumns(6);
            yield Field::new('parent2Phone1', 'Phone 1')->hideOnIndex()->setColumns(4);
            yield Field::new('parent2Phone2', 'Phone 2')->hideOnIndex()->setColumns(4);
            yield Field::new('parent2Email', 'Email')->hideOnIndex()->setColumns(4);
            
            yield FormField::addPanel('Emergency Contact');
            yield Field::new('ecFirstName', 'First Name')->hideOnIndex()->setColumns(6);
            yield Field::new('ecLastName', 'Last Name')->hideOnIndex()->setColumns(6);
            yield Field::new('ecRelationship', 'Relationship')->hideOnIndex();
            yield Field::new('ecPhone1', 'Phone 1')->hideOnIndex()->setColumns(6);
            yield Field::new('ecPhone2', 'Phone 2')->hideOnIndex()->setColumns(6);
            
            yield FormField::addPanel('Bus');
            yield BooleanField::new('takingBus', 'Taking the Bus')->hideOnIndex();
            yield Field::new('busToContact', 'Thursday Contact Person')->hideOnIndex()->setColumns(6);
            yield Field::new('busToPhone', 'Thursday Contact Phone')->hideOnIndex()->setColumns(6);
            yield Field::new('busFromContact', 'Sunday Contact Person')->hideOnIndex()->setColumns(6);
            yield Field::new('busFromPhone', 'Sunday Contact Phone')->hideOnIndex()->setColumns(6);
            
            yield FormField::addTab('Assignments');
            yield AssociationField::new('letterGroup')->setRequired(false);
            yield AssociationField::new('dormRoom')->setRequired(false);
            yield Field::new('thankyouName', 'Thank You Name')->hideOnIndex()->setColumns(6);
            yield Field::new('thankyouType', 'Thank You Type')->hideOnIndex()->setColumns(6);
            
            yield FormField::addTab('Health & Medical');
            yield TextareaField::new('currentConditions')->hideOnIndex();
            yield TextareaField::new('exerciseLimits')->hideOnIndex();
            yield TextareaField::new('dietRestrictions')->hideOnIndex();
            yield TextareaField::new('dietInfo')->hideOnIndex();
            yield Field::new('dietSeverity', 'Diet Restriction Severity')->hideOnIndex();
            yield TextareaField::new('allergies', 'Other Allergies')->hideOnIndex();
            yield TextareaField::new('medAllergies', 'Drug Allergies')->hideOnIndex();
            yield TextareaField::new('approvedOtc', 'Approved OTC Meds')->hideOnIndex();
            yield TextareaField::new('currentRx', 'Current Prescriptions')->hideOnIndex();
            
            yield FormField::addTab('Paperwork & Backend');
            yield BooleanField::new('checkin_paperwork', 'PSMs Received')->hideOnIndex();
            
            yield BooleanField::new('checkedIn')->hideOnIndex()->setColumns(6);
            yield BooleanField::new('checkedOut')->hideOnIndex()->setColumns(6);
            
            yield BooleanField::new('cg_form', 'Comings and Goings Form Received')->hideOnIndex()->setColumns(6);
            yield BooleanField::new('checkin_doctor_form', 'Medication Verification Form Received')->hideOnIndex()->setColumns(6);
            
            yield FormField::addPanel('Key Deposit');
            yield BooleanField::new('checkin_deposit', 'Deposit Paid')->hideOnIndex();
            yield ChoiceField::new('checkin_deposit_method', 'Deposit Method')->hideOnIndex()->setChoices([
                'PayPal' => 'PayPal',
                'Check' => 'Check',
                'Cash' => 'Cash',
                'QuickBooks' => 'QuickBooks',
                'Waived' => 'Waived'
            ]);
            yield Field::new('checkin_deposit_notes', 'Key Deposit Notes')->hideOnIndex();
            yield ChoiceField::new('checkout_deposit_decision')->hideOnIndex()->setChoices([
                'Movie' => 'movie',
                'Return' => 'return',
                'Lost Keys' => 'lost'
            ]);
            
            yield FormField::addPanel('Pre-Seminar Calls');
            yield BooleanField::new('juniorCallMade', 'Call Made')->hideOnIndex();
            yield ChoiceField::new('juniorCallDisposition', 'Call Disposition')->hideOnIndex()->setRequired(false)->setChoices([
                'Spoke with ambassador' => 'spoke_with_ambassador',
                'Spoke with ambassador\'s family, will call back' => 'spoke_with_family',
                'Left a message, will call back' => 'left_message',
                'Wrong number' => 'wrong_number',
                'Other (please explain in notes)' => 'other'
            ]);
            yield TextareaField::new('juniorCallNotes', 'Call Notes')->hideOnIndex();
            
            yield FormField::addPanel('Bed Checks');
            yield BooleanField::new('bedThursday', 'Thursday')->hideOnIndex()->setColumns(4);
            yield BooleanField::new('bedFriday', 'Friday')->hideOnIndex()->setColumns(4);
            yield BooleanField::new('bedSaturday', 'Saturday')->hideOnIndex()->setColumns(4);
            
            // Evaluation tab removed — eval data now lives in the AmbassadorEvaluation
            // entity and is managed via "Ambassador Evaluations" in the admin sidebar.
            
    }
   
}
