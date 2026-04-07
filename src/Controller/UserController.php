<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\StaffAssignment;
use App\Entity\LetterGroup;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\StaffAssignmentRepository;
use App\Service\SeminarYearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $saRepo->findActiveByYear($yearService->getActiveSeminarYear()),
        ]);
    }

    #[Route('/faces', name: 'app_user_faces', methods: ['GET'])]
    public function facesAction(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        return $this->render('user/faces.html.twig', [
            'users' => $saRepo->findActiveByYear($yearService->getActiveSeminarYear()),
        ]);
    }

    #[Route('/requirements/{id}', name: 'app_user_edit_requirements', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_BOARD')]
    public function requirementsEditAction(Request $request, StaffAssignment $staffAssignment, StaffAssignmentRepository $saRepo): Response
    {
        $form = $this->createForm('App\Form\UserRequirementsType', $staffAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saRepo->save($staffAssignment, true);
            return $this->redirectToRoute('app_user_requirements', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_requirements.html.twig', [
            'user' => $staffAssignment,
            'form' => $form,
        ]);
    }

    #[Route('/requirements', name: 'app_user_requirements', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function requirementsAction(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        $year = $yearService->getActiveSeminarYear();
        return $this->render('user/requirements.html.twig', [
            'users' => $saRepo->findActiveByYear($year),
            'lastUpdated' => $saRepo->getPsmsLastUpdated($year),
        ]);
    }

    #[Route('/assignments/checkin', name: 'app_user_checkin_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function checkinAssignmentsAction(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        return $this->render('user/checkin_assignments.html.twig', [
            'users' => $saRepo->findActiveByYear($yearService->getActiveSeminarYear()),
        ]);
    }

    #[Route('/assignments/closingceremony', name: 'app_user_cc_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function ccAssignmentsAction(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        return $this->render('user/cc_assignments.html.twig', [
            'users' => $saRepo->findActiveByYear($yearService->getActiveSeminarYear()),
        ]);
    }

    #[Route('/assignments/checkout', name: 'app_user_checkout_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function checkoutAssignmentsAction(StaffAssignmentRepository $saRepo, SeminarYearService $yearService): Response
    {
        return $this->render('user/checkout_assignments.html.twig', [
            'users' => $saRepo->findActiveByYear($yearService->getActiveSeminarYear()),
        ]);
    }

    #[Route('/assignments/my', name: 'app_user_my_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function myAssignmentsAction(): Response
    {
        return $this->render('user/my_assignments.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/evaluations/{letter}', name: 'staffeval_index', methods: ['GET'])]
    public function staffEvalIndex(LetterGroup $letterGroup): Response
    {
        if ($this->getUser()->getLetterGroup() == $letterGroup) {
            return $this->render('evaluations/staff.html.twig', [
                'group' => $letterGroup,
            ]);
        } else {
            $this->addFlash('error', 'You are not authorized to view this page. If you think this is in error, please contact the Director of Facilitators.');
            return $this->render('default/flash.html.twig');
        }
    }

    #[Route('/evaluations/{id}/do', name: 'staffeval_edit', methods: ['GET', 'POST'])]
    public function staffEvalEditAction(Request $request, StaffAssignment $staffAssignment, StaffAssignmentRepository $saRepo): Response
    {
        if ($this->getUser()->getLetterGroup() == $staffAssignment->getLetterGroup()) {
            $editForm = $this->createForm('App\Form\StaffEvaluationType', $staffAssignment);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $staffAssignment->setEvalStatus(true);
                $saRepo->save($staffAssignment, true);
                return $this->redirectToRoute('staffeval_index', ['letter' => $this->getUser()->getLetterGroup()->getLetter()]);
            }

            return $this->render('evaluations/edit_staff.html.twig', [
                'user' => $staffAssignment,
                'edit_form' => $editForm->createView(),
            ]);
        } else {
            $this->addFlash('error', 'You are not authorized to view this page. If you feel this is in error, please contact the Director of Facilitators.');
            return $this->render('default/flash.html.twig');
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
