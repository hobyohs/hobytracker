<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\StaffAssignment;
use App\Entity\LetterGroup;
use App\Repository\UserRepository;
use App\Repository\StaffAssignmentRepository;
use App\Service\SeminarYearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/assignments', name: 'app_user_duty_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function dutyAssignmentsAction(
        Request $request,
        StaffAssignmentRepository $saRepo,
        SeminarYearService $yearService,
        \App\Repository\BedCheckAssignmentRepository $bcaRepo,
        \App\Repository\DormRoomRepository $dormRoomRepo,
    ): Response {
        $year = $yearService->getActiveSeminarYear();
        $users = $saRepo->findActiveByYear($year);
        $tab = $request->query->get('tab', 'checkin');
        if (!in_array($tab, ['checkin', 'checkout', 'cc', 'bedchecks'])) {
            $tab = 'checkin';
        }

        // For the bed checks tab, build the assignment grid
        $bcFloors = [];
        $bcAssignments = [];
        if ($tab === 'bedchecks') {
            $allRooms = $dormRoomRepo->findAllOrderedForBedChecks();
            $seen = [];
            foreach ($allRooms as $room) {
                if ($room->getAmbassadors()->isEmpty()) continue;
                $key = $room->getDorm() . '|||' . $room->getFloor();
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $bcFloors[] = ['dorm' => $room->getDorm(), 'floor' => $room->getFloor()];
            }
            $bcAssignments = $bcaRepo->findAllByYearIndexed($year);
        }

        return $this->render('user/duty_assignments.html.twig', [
            'users'         => $users,
            'tab'           => $tab,
            'bcFloors'      => $bcFloors,
            'bcAssignments' => $bcAssignments,
            'bcNights'      => \App\Entity\BedCheckAssignment::NIGHTS,
        ]);
    }

    #[Route('/assignments/update', name: 'app_user_duty_assignment_update', methods: ['POST'])]
    #[IsGranted('ROLE_BOARD')]
    public function dutyAssignmentUpdateAction(
        Request $request,
        StaffAssignmentRepository $saRepo,
        EntityManagerInterface $em,
    ): JsonResponse {
        $allowedFields = [
            'assignmentCheckIn',
            'assignmentCheckInNotes',
            'assignmentCheckOut',
            'assignmentCheckOutNotes',
            'assignmentClosingCeremonies',
            'assignmentClosingCeremoniesNotes',
        ];

        $id    = (int) $request->request->get('id');
        $field = $request->request->get('field', '');
        $value = trim($request->request->get('value', ''));

        if (!in_array($field, $allowedFields, true)) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid field'], 400);
        }

        $sa = $saRepo->find($id);
        if (!$sa) {
            return new JsonResponse(['success' => false, 'error' => 'Staff not found'], 404);
        }

        // Normalize empty string to null for clean DB storage
        $sa->{'set' . ucfirst($field)}($value === '' ? null : $value);
        $em->flush();

        return new JsonResponse(['success' => true, 'value' => $value]);
    }

    #[Route('/assignments/my', name: 'app_user_my_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function myAssignmentsAction(\App\Repository\BedCheckAssignmentRepository $bcaRepo, SeminarYearService $yearService): Response
    {
        $user = $this->getUser();
        $bcAssignments = $bcaRepo->findByUserAndYear($user->getId(), $yearService->getActiveSeminarYear());

        // Group by night for template display
        $bcByNight = ['Thursday' => [], 'Friday' => [], 'Saturday' => []];
        foreach ($bcAssignments as $a) {
            $bcByNight[$a->getNight()][] = $a->getDorm() . ' — Floor ' . $a->getFloor();
        }

        return $this->render('user/my_assignments.html.twig', [
            'user'      => $user,
            'bcByNight' => $bcByNight,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
