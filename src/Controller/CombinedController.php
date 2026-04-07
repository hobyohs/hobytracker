<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AmbassadorRepository;
use App\Repository\StaffAssignmentRepository;
use App\Service\SeminarYearService;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lists')]
class CombinedController extends AbstractController
{
    #[Route('/all_groups', name: 'all_groups', methods: ['GET'])]
    public function groupAction(AmbassadorRepository $ambassadorRepository, StaffAssignmentRepository $saRepo, SeminarYearService $yearService)
    {
        $year = $yearService->getActiveSeminarYear();
        $ambassadors = $ambassadorRepository->findAllWithGroups();
        $staffAssignments = $saRepo->findActiveByYear($year);

        $people = [];

        foreach ($ambassadors as $ambassador) {
            $people[] = [
                'type' => 'ambassador',
                'showpath' => 'app_ambassador_show',
                'id' => $ambassador->getId(),
                'lastName' => $ambassador->getLastName(),
                'firstName' => $ambassador->getConsolidatedFirstName(),
                'school' => $ambassador->getSchool(),
                'role' => 'Ambassador',
                'group' => $ambassador->getLetterGroup(),
                'sort' => 4,
            ];
        }

        foreach ($staffAssignments as $sa) {
            if ($sa->getLetterGroup() !== null) {
                $people[] = [
                    'type' => 'user',
                    'showpath' => 'app_user_show',
                    'id' => $sa->getUserId(),
                    'lastName' => $sa->getLastName(),
                    'firstName' => $sa->getConsolidatedFirstName(),
                    'group' => $sa->getLetterGroup(),
                    'school' => '',
                    'role' => $sa->getPosition(),
                    'sort' => $sa->getSortRank(),
                ];
            }
        }

        return $this->render('combined/letterGroups.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/emergency', name: 'emergency_contacts', methods: ['GET'])]
    public function ecAction(AmbassadorRepository $ambassadorRepository, StaffAssignmentRepository $saRepo, SeminarYearService $yearService)
    {
        $year = $yearService->getActiveSeminarYear();
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $staffAssignments = $saRepo->findActiveByYear($year);

        $people = [];

        foreach ($ambassadors as $ambassador) {
            $people[] = [
                'type' => 'ambassador',
                'showpath' => 'app_ambassador_show',
                'id' => $ambassador->getId(),
                'lastName' => $ambassador->getLastName(),
                'firstName' => $ambassador->getConsolidatedFirstName(),
                'prefName' => $ambassador->getPrefName(),
                'group' => $ambassador->getLetterGroup(),
                'ecFirstName' => $ambassador->getEcFirstName(),
                'ecLastName' => $ambassador->getEcLastName(),
                'ecRelationship' => $ambassador->getEcRelationship(),
                'ecPhone1' => $ambassador->getEcPhone1(),
                'ecPhone2' => $ambassador->getEcPhone2(),
            ];
        }

        foreach ($staffAssignments as $sa) {
            $people[] = [
                'type' => 'user',
                'showpath' => 'app_user_show',
                'id' => $sa->getUserId(),
                'lastName' => $sa->getLastName(),
                'firstName' => $sa->getConsolidatedFirstName(),
                'prefName' => $sa->getPrefName(),
                'group' => 'Staff',
                'ecFirstName' => $sa->getEcFirstName(),
                'ecLastName' => $sa->getEcLastName(),
                'ecRelationship' => $sa->getEcRelationship(),
                'ecPhone1' => $sa->getEcPhone1(),
                'ecPhone2' => $sa->getEcPhone2(),
            ];
        }

        return $this->render('combined/ec.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/dorms', name: 'dorm_list', methods: ['GET'])]
    public function dormAction(AmbassadorRepository $ambassadorRepository, StaffAssignmentRepository $saRepo, SeminarYearService $yearService)
    {
        $year = $yearService->getActiveSeminarYear();
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $staffAssignments = $saRepo->findActiveByYear($year);

        $people = [];

        foreach ($ambassadors as $ambassador) {
            if (!empty($ambassador->getDorm()) && !empty($ambassador->getRoom())) {
                $people[] = [
                    'type' => 'ambassador',
                    'showpath' => 'app_ambassador_show',
                    'id' => $ambassador->getId(),
                    'lastName' => $ambassador->getLastName(),
                    'firstName' => $ambassador->getConsolidatedFirstName(),
                    'group' => $ambassador->getLetterGroup(),
                    'shirtSize' => $ambassador->getShirtSize(),
                    'dormRoom' => $ambassador->getDormRoom(),
                    'dorm' => $ambassador->getDorm(),
                    'room' => $ambassador->getRoom(),
                ];
            }
        }

        foreach ($staffAssignments as $sa) {
            if (!empty($sa->getDorm()) && !empty($sa->getRoom())) {
                $people[] = [
                    'type' => 'user',
                    'showpath' => 'app_user_show',
                    'id' => $sa->getUserId(),
                    'lastName' => $sa->getLastName(),
                    'firstName' => $sa->getConsolidatedFirstName(),
                    'shirtSize' => $sa->getShirtSize(),
                    'dormRoom' => $sa->getDormRoom(),
                    'group' => 'Staff',
                    'dorm' => $sa->getDorm(),
                    'room' => $sa->getRoom(),
                ];
            }
        }

        return $this->render('combined/dorms.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/dietary', name: 'dietary', methods: ['GET'])]
    public function dietAction(AmbassadorRepository $ambassadorRepository, StaffAssignmentRepository $saRepo, SeminarYearService $yearService)
    {
        $year = $yearService->getActiveSeminarYear();
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $staffAssignments = $saRepo->findActiveByYear($year);

        $nullDietRestrictions = [
            "0",
            "I Eat Everything, no restrictions",
            "I Eat Everything no restrictions;",
            "I Eat Everything, no restrictions;",
            "None",
            "I Eat Everything, no restrictions,",
        ];

        $people = [];

        foreach ($ambassadors as $ambassador) {
            $restrictions = !in_array($ambassador->getDietRestrictions(), $nullDietRestrictions) ? $ambassador->getDietRestrictions() : null;
            if (!empty($restrictions) || !empty($ambassador->getDietInfo()) || !empty($ambassador->getDietSeverity())) {
                $people[] = [
                    'type' => 'ambassador',
                    'showpath' => 'app_ambassador_show',
                    'id' => $ambassador->getId(),
                    'lastName' => $ambassador->getLastName(),
                    'firstName' => $ambassador->getConsolidatedFirstName(),
                    'group' => $ambassador->getLetterGroup(),
                    'dietRestrictions' => $restrictions,
                    'dietInfo' => $ambassador->getDietInfo(),
                    'dietSeverity' => $ambassador->getDietSeverity(),
                ];
            }
        }

        foreach ($staffAssignments as $sa) {
            $restrictions = !in_array($sa->getDietRestrictions(), $nullDietRestrictions) ? $sa->getDietRestrictions() : null;
            if (!empty($restrictions) || !empty($sa->getDietInfo()) || !empty($sa->getDietSeverity())) {
                $people[] = [
                    'type' => 'user',
                    'showpath' => 'app_user_show',
                    'id' => $sa->getUserId(),
                    'lastName' => $sa->getLastName(),
                    'firstName' => $sa->getConsolidatedFirstName(),
                    'group' => 'Staff',
                    'dietRestrictions' => $restrictions,
                    'dietInfo' => $sa->getDietInfo(),
                    'dietSeverity' => $sa->getDietSeverity(),
                ];
            }
        }

        return $this->render('combined/dietary.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/medical', name: 'medical', methods: ['GET'])]
    public function medicalAction(AmbassadorRepository $ambassadorRepository, StaffAssignmentRepository $saRepo, SeminarYearService $yearService)
    {
        $year = $yearService->getActiveSeminarYear();
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $staffAssignments = $saRepo->findActiveByYear($year);

        $people = [];

        foreach ($ambassadors as $ambassador) {
            $people[] = [
                'type' => 'ambassador',
                'showpath' => 'app_ambassador_show',
                'id' => $ambassador->getId(),
                'lastName' => $ambassador->getLastName(),
                'firstName' => $ambassador->getConsolidatedFirstName(),
                'group' => $ambassador->getLetterGroup(),
                'currentConditions' => $ambassador->getCurrentConditions(),
                'exerciseLimits' => $ambassador->getExerciseLimits(),
                'allergies' => $ambassador->getAllergies(),
                'medAllergies' => $ambassador->getMedAllergies(),
                'currentRx' => $ambassador->getCurrentRx(),
            ];
        }

        foreach ($staffAssignments as $sa) {
            $people[] = [
                'type' => 'user',
                'showpath' => 'app_user_show',
                'id' => $sa->getUserId(),
                'lastName' => $sa->getLastName(),
                'firstName' => $sa->getConsolidatedFirstName(),
                'group' => 'Staff',
                'currentConditions' => $sa->getCurrentConditions(),
                'exerciseLimits' => $sa->getExerciseLimits(),
                'allergies' => $sa->getAllergies(),
                'medAllergies' => $sa->getMedAllergies(),
                'currentRx' => $sa->getCurrentRx(),
            ];
        }

        return $this->render('combined/medical.html.twig', [
            'people' => $people,
        ]);
    }
}
