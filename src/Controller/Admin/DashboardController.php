<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ambassador;
use App\Repository\AmbassadorRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\ComingsAndGoings;
use App\Entity\LetterGroup;
use App\Entity\Applicant;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AmbassadorCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
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
            MenuItem::linkToCrud('Staff', 'fas fa-id-badge', User::class),
            MenuItem::linkToCrud('Groups', 'fas fa-people-group', LetterGroup::class),
            MenuItem::linkToCrud('Comings and Goings', 'fas fa-person-walking-luggage', ComingsAndGoings::class),
            MenuItem::linkToRoute('Groups Demographics', 'fas fa-chart-pie', 'admin_group_demo_report'),        
            MenuItem::linkToRoute('Shirt Sizes', 'fas fa-shirt', 'admin_shirt_size_report'),               
            MenuItem::linkToCrud('Staff Applications', 'fas fa-clipboard-question', Applicant::class),    
            MenuItem::linkToRoute('Evaluations', 'fas fa-gavel', 'admin_evaluations'),    
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
    public function shirtSizesAction(UserRepository $userRepository, AmbassadorRepository $ambassadorRepository): Response
    {
        return $this->render('bundles/EasyAdminBundle/shirtSizeReport.html.twig', array(
            'ambassador_sizes' => $ambassadorRepository->shirtSizeReport(),
            'user_sizes' => $userRepository->shirtSizeReport(),
            'ambassador_nulls' => $ambassadorRepository->nullShirtSizes(),
            'user_nulls' => $userRepository->nullShirtSizes()
        ));
    }
    
    #[Route('/admin/evaluations', name: 'admin_evaluations')]
    public function evaluationsAction(UserRepository $userRepository, AmbassadorRepository $ambassadorRepository): Response
    {
        return $this->render('bundles/EasyAdminBundle/evaluations.html.twig', array(
            'ambassador_evaluations' => $ambassadorRepository->findAllWithEvaluations(),
            'user_evaluations' => $userRepository->findAllWithEvaluations(),
            'ambassador_nulls' => $ambassadorRepository->nullEvaluations(),
            'user_nulls' => $userRepository->nullEvaluations()
        ));
    }
    
}
