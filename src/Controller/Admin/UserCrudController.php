<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
        
    private $adminUrlGenerator;
    
    private UserPasswordHasherInterface $hasher;
    
    public function __construct(AdminUrlGenerator $adminUrlGenerator, UserPasswordHasherInterface $hasher)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->hasher = $hasher;
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
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
            $user = $entityManager->find($className, $id);
            $user->setBgCheckSubmitted(TRUE);
            $user->setBgCheckComplete(TRUE);
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Successfully marked '.count($batchActionDto->getEntityIds()).' volunteers as having cleared their background checks.');
        //return $this->redirect($batchActionDto->getReferrerUrl());
        $url = $this->adminUrlGenerator
        ->setController(AmbassadorCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();
        
        return $this->redirect($url);
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        
        return $crud
            ->setEntityLabelInSingular('Staff Member')
            ->setEntityLabelInPlural('Staff')
            ->setPaginatorPageSize(250)
            ->setDefaultSort(['lastName' => 'ASC'])
        ;

    }

    public function configureFields(string $pageName): iterable
    {
        
        yield FormField::addTab('Volunteer Info');
        yield IdField::new('id')->onlyOnIndex();
        yield Field::new('firstName')->setColumns(4);
        yield Field::new('prefName', 'Preferred Name')->hideOnIndex()->setColumns(4);
        yield Field::new('lastName')->setColumns(4);
        yield Field::new('cellPhone')->hideOnIndex()->setColumns(6);
        yield Field::new('age')->hideOnIndex()->setColumns(6)->setHelp('This is the volunteer\'s age as of the seminar and is used to determine permissions in HOBYtracker.');
        yield AvatarField::new('photo')->hideOnIndex()->setColumns(6);
        yield ChoiceField::new('shirtSize')->hideOnIndex()->setChoices([
            'Small' => 'S',
            'Medium' => 'M',
            'Large' => 'L',
            'X-Large' => 'XL',
            'XX-Large' => 'XXL',
            'XXX-Large' => 'XXXL',
            
        ]);
        
        yield FormField::addTab('Security');
        yield Field::new('email')->hideOnIndex()->setHelp('Note that this is also the volunteer\'s HOBYTracker username, so make sure they are aware if you change it.');
        yield Field::new('newPassword', 'Password')->hideOnIndex()->setFormType(RepeatedType::class)->setColumns(6)->setFormTypeOptions( [
                'type'            => PasswordType::class,
                'first_options'   => [ 'label' => 'Password' ],
                'second_options'  => [ 'label' => 'Repeat password' ],
                'error_bubbling'  => true,
                'invalid_message' => 'The password fields do not match.',
            ] );
        
        yield FormField::addTab('Role & Assignments');
        yield Field::new('position')->setColumns(6);
        yield AssociationField::new('letterGroup')->setRequired(false)->setColumns(6);
        yield ChoiceField::new('roles', 'Permissions')->hideOnIndex()->allowMultipleChoices()->setChoices([
            'Basic User' => 'ROLE_USER',
            'Board/Senior Staff' => 'ROLE_BOARD',
            'Director of Facilitators' => 'ROLE_DOF',
            'Nurse' => 'ROLE_NURSE',
            'Admin' => 'ROLE_ADMIN',
            
        ])
        ->setHelp('Nurse and DoF automatically inherit Board permissions.')
        ;
        yield AssociationField::new('dormRoom')->setRequired(false);
        yield FormField::addPanel('Duty Assignments');
        yield Field::new('assignmentCheckIn', 'Check In Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentCheckInNotes', 'Check In Assignment Notes')->hideOnIndex()->setColumns(6);
        yield Field::new('assignmentClosingCeremonies', 'Closing Ceremony Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentClosingCeremoniesNotes', 'Closing Ceremony Assignment Notes')->hideOnIndex()->setColumns(6);
        yield Field::new('assignmentCheckOut', 'Check Out Assignment')->hideOnIndex()->setColumns(6);
        yield TextareaField::new('assignmentCheckOutNotes', 'Check Out Assignment Notes')->hideOnIndex()->setColumns(6);
        
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
        
        yield FormField::addTab('Evaluation');
        yield ArrayField::new('cofacilitators')->hideOnIndex()->setFormTypeOption('disabled','disabled');
        yield TextareaField::new('eval_pros', 'What were this facilitator\'s greatest strengths?')->hideOnIndex()->setFormTypeOption('disabled','disabled');
        yield TextareaField::new('eval_cons', 'What obstacles, challenges, or weaknesses did this facilitator face? Did they grow and address them, or do the challenges remain?')->hideOnIndex()->setFormTypeOption('disabled','disabled');
        yield ChoiceField::new('eval_discussions', 'This facilitator was effective in helping the group start discussions.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_enthusiastic', 'This facilitator was enthusiastic and energetic throughout the seminar.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_organized', 'This facilitator was personally organized and helped keep you and the rest of the group on track.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_equally', 'This facilitator treated each ambassador equally and didn\'t play favorites.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_responsible', 'This facilitator was responsible and effective in protecting the group\'s physical and emotional well-being.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_attentive', 'This facilitator was attentive and interested during all speakers, panels, and activities. They did not step out frequently or use their phone for non-HOBY purposes in front of the group.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_include', 'This facilitator actively worked to include all ambassadors in group discussions and activities.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_professional', 'This facilitator acted professionally and maturely throughout the seminar. They were an excellent representative of HOBY to our host, partners, and parents.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield ChoiceField::new('eval_punctual', 'This facilitator was punctual throughout the seminar both to group activities and staff meetings. You or the group never waited for them to arrive.')->hideOnIndex()->setFormTypeOption('disabled','disabled')->setChoices([
            'Strongly Disagree' => 1,
            'Disagree' => 2,
            'Neutral' => 3,
            'Agree' => 4,
            'Strongly Agree' => 5,
        ]);
        yield TextareaField::new('eval_whynot', 'Is there any reason this facilitator should not be invited back next year? Why?')->hideOnIndex()->setFormTypeOption('disabled','disabled');
        yield TextareaField::new('eval_comments', 'Please share anything else you\'d like us to know about this facilitator.')->hideOnIndex()->setFormTypeOption('disabled','disabled');
        
    
    }
    
    // These next three functions are all for setting the user password... believe it or not
    public function createNewFormBuilder( 
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context 
    ): FormBuilderInterface {
        $formBuilder = parent::createNewFormBuilder( $entityDto, $formOptions, $context );
        $this->addEncodePasswordEventListener( $formBuilder );
        return $formBuilder;
    }
    
    public function createEditFormBuilder( 
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context 
    ): FormBuilderInterface {
        $formBuilder   = parent::createEditFormBuilder( $entityDto, $formOptions, $context );
        $this->addEncodePasswordEventListener( $formBuilder);
        return $formBuilder;
    }
    
    protected function addEncodePasswordEventListener( 
        FormBuilderInterface $formBuilder
    ): void {
        $formBuilder->addEventListener( 
            FormEvents::SUBMIT, 
            function ( FormEvent $event ) {
                $user = $event->getData();
                $plainPassword = $user->getNewPassword();
                if ($plainPassword != null) {
                    $user->setPassword( $this->hasher->hashPassword( $user, $plainPassword ) );
                    $this->addFlash('success', 'Successfully updated password.');
                    $user->eraseCredentials();
                }
            } 
        );
    }
    
}