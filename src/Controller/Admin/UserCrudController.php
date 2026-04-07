<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User Account')
            ->setEntityLabelInPlural('User Accounts')
            ->setPaginatorPageSize(250)
            ->setDefaultSort(['lastName' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Identity');
        yield IdField::new('id')->onlyOnIndex();
        yield Field::new('firstName')->setColumns(4);
        yield Field::new('prefName', 'Preferred Name')->hideOnIndex()->setColumns(4);
        yield Field::new('lastName')->setColumns(4);
        yield Field::new('cellPhone')->hideOnIndex()->setColumns(6);
        yield Field::new('gender')->hideOnIndex()->setColumns(6);
        yield Field::new('pronouns')->hideOnIndex()->setColumns(6);

        yield FormField::addTab('Security');
        yield Field::new('email')->setColumns(6)->setHelp('This is also the volunteer\'s HOBYTracker username.');
        yield Field::new('newPassword', 'Password')->hideOnIndex()->setFormType(RepeatedType::class)->setColumns(6)->setFormTypeOptions([
            'type' => PasswordType::class,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat password'],
            'error_bubbling' => true,
            'invalid_message' => 'The password fields do not match.',
        ]);
        yield ChoiceField::new('roles', 'Permissions')->hideOnIndex()->allowMultipleChoices()->setChoices([
            'Basic User' => 'ROLE_USER',
            'Board/Senior Staff' => 'ROLE_BOARD',
            'Director of Facilitators' => 'ROLE_DOF',
            'Nurse' => 'ROLE_NURSE',
            'Admin' => 'ROLE_ADMIN',
        ])->setHelp('Nurse and DoF automatically inherit Board permissions.');
    }

    // Password hashing on form submit
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder);
        return $formBuilder;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder);
        return $formBuilder;
    }

    protected function addEncodePasswordEventListener(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            $plainPassword = $user->getNewPassword();
            if ($plainPassword != null) {
                $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
                $user->eraseCredentials();
            }
        });
    }
}
