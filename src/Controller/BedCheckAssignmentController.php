<?php

namespace App\Controller;

use App\Entity\BedCheckAssignment;
use App\Repository\StaffAssignmentRepository;
use App\Service\SeminarYearService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bedcheck-assignments')]
#[IsGranted('ROLE_BOARD')]
class BedCheckAssignmentController extends AbstractController
{
    public function __construct(
        private SeminarYearService $yearService,
        private EntityManagerInterface $em,
    ) {}

    /**
     * JSON endpoint for Tom Select staff search.
     */
    #[Route('/search', name: 'bedcheck_assignments_search', methods: ['GET'])]
    public function search(Request $request, StaffAssignmentRepository $saRepo): JsonResponse
    {
        $q = trim($request->query->get('q', ''));
        if (strlen($q) < 1) {
            return new JsonResponse([]);
        }

        $year = $this->yearService->getActiveSeminarYear();
        $results = $saRepo->searchActiveByName($q, $year);

        $data = [];
        foreach ($results as $sa) {
            $data[] = [
                'id'   => $sa->getId(),
                'name' => $sa->getConsolidatedFirstName() . ' ' . $sa->getLastName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * AJAX: add a bed check assignment.
     */
    #[Route('/add', name: 'bedcheck_assignments_add', methods: ['POST'])]
    public function add(Request $request, StaffAssignmentRepository $saRepo): JsonResponse
    {
        $saId  = (int) $request->request->get('staff_assignment_id');
        $dorm  = $request->request->get('dorm');
        $floor = $request->request->get('floor');
        $night = $request->request->get('night');
        $year  = $this->yearService->getActiveSeminarYear();

        if (!in_array($night, BedCheckAssignment::NIGHTS)) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid night'], 400);
        }

        $sa = $saRepo->find($saId);
        if (!$sa) {
            return new JsonResponse(['success' => false, 'error' => 'Staff not found'], 404);
        }

        $bca = new BedCheckAssignment();
        $bca->setStaffAssignment($sa);
        $bca->setDorm($dorm);
        $bca->setFloor($floor);
        $bca->setNight($night);
        $bca->setSeminarYear($year);

        try {
            $this->em->persist($bca);
            $this->em->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return new JsonResponse(['success' => false, 'error' => 'Already assigned'], 409);
        }

        return new JsonResponse([
            'success' => true,
            'id'      => $bca->getId(),
            'name'    => $bca->getStaffName(),
        ]);
    }

    /**
     * AJAX: remove a bed check assignment.
     */
    #[Route('/remove/{id}', name: 'bedcheck_assignments_remove', methods: ['POST'])]
    public function remove(BedCheckAssignment $bca): JsonResponse
    {
        $this->em->remove($bca);
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}
