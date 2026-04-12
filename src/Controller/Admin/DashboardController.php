<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Ambassador;
use App\Entity\AmbassadorEvaluation;
use App\Entity\StaffAssignment;
use App\Entity\StaffEvaluation;
use App\Entity\User;
use App\Entity\ComingsAndGoings;
use App\Entity\LetterGroup;
use App\Entity\Applicant;
use App\Repository\AmbassadorRepository;
use App\Repository\StaffAssignmentRepository;
use App\Service\SeminarYearService;

class DashboardController extends AbstractDashboardController
{
    private SeminarYearService $seminarYearService;

    public function __construct(SeminarYearService $seminarYearService)
    {
        $this->seminarYearService = $seminarYearService;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AmbassadorCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HOBYTracker')
            ->setFaviconPath('/assets/images/favicon/favicon.ico')
            ->disableDarkMode()
        ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Ambassadors', 'fas fa-person', Ambassador::class),
            MenuItem::linkToCrud('Staff Assignments', 'fas fa-id-badge', StaffAssignment::class),
            MenuItem::linkToCrud('User Accounts', 'fas fa-user-shield', User::class),
            MenuItem::linkToCrud('Groups', 'fas fa-people-group', LetterGroup::class),
            MenuItem::linkToCrud('Comings and Goings', 'fas fa-person-walking-luggage', ComingsAndGoings::class),
            MenuItem::linkToRoute('Groups Demographics', 'fas fa-chart-pie', 'admin_group_demo_report'),
            MenuItem::linkToRoute('Shirt Sizes', 'fas fa-shirt', 'admin_shirt_size_report'),
            MenuItem::linkToCrud('Staff Applications', 'fas fa-clipboard-question', Applicant::class),
            MenuItem::section('Evaluations'),
            MenuItem::linkToCrud('Ambassador Evaluations', 'fas fa-star', AmbassadorEvaluation::class),
            MenuItem::linkToCrud('Staff Evaluations', 'fas fa-gavel', StaffEvaluation::class),
            MenuItem::linkToRoute('Evaluations Report', 'fas fa-chart-bar', 'admin_evaluations'),
        ];
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->showEntityActionsInlined()
        ;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setAvatarUrl($user->getPhoto());
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL,
                fn (Action $action) => $action->setIcon('fa-solid fa-eye')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::EDIT,
                fn (Action $action) => $action->setIcon('fa fa-pencil')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::DELETE,
                fn (Action $action) => $action->setIcon('fa fa-trash-can')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setIcon('fa fa-plus'))
        ;
    }

    #[Route('/admin/shirt_sizes', name: 'admin_shirt_size_report')]
    public function shirtSizesAction(StaffAssignmentRepository $saRepo, AmbassadorRepository $ambassadorRepository): Response
    {
        $year = $this->seminarYearService->getActiveSeminarYear();
        return $this->render('bundles/EasyAdminBundle/shirtSizeReport.html.twig', [
            'ambassador_sizes' => $ambassadorRepository->shirtSizeReport(),
            'user_sizes' => $saRepo->shirtSizeReport($year),
            'ambassador_nulls' => $ambassadorRepository->nullShirtSizes(),
            'user_nulls' => $saRepo->nullShirtSizes($year),
        ]);
    }

    #[Route('/admin/evaluations', name: 'admin_evaluations')]
    public function evaluationsAction(StaffAssignmentRepository $saRepo, AmbassadorRepository $ambassadorRepository): Response
    {
        $year = $this->seminarYearService->getActiveSeminarYear();
        return $this->render('bundles/EasyAdminBundle/evaluations.html.twig', [
            'ambassador_evaluations' => $ambassadorRepository->findAllWithEvaluations(),
            'user_evaluations' => $saRepo->findAllWithEvaluations($year),
            'ambassador_nulls' => $ambassadorRepository->nullEvaluations(),
            'user_nulls' => $saRepo->nullEvaluations($year),
        ]);
    }
}
