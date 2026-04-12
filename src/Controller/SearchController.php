<?php

namespace App\Controller;

use App\Repository\AmbassadorRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SearchController extends AbstractController
{
    #[Route('/search/people', name: 'app_search_people', methods: ['GET'])]
    public function people(
        Request $request,
        AmbassadorRepository $ambRepo,
        UserRepository $userRepo
    ): JsonResponse {
        $query = trim($request->query->get('q', ''));
        if (strlen($query) < 2) {
            return new JsonResponse(['people' => []]);
        }

        $results = [];

        foreach ($ambRepo->searchByName($query) as $amb) {
            $group = $amb->getLetterGroup();
            $results[] = [
                'type'      => 'ambassador',
                'name'      => $amb->getConsolidatedFirstName() . ' ' . $amb->getLastName(),
                'sub'       => $amb->getSchool(),
                'photo'     => $amb->getPhoto(),
                'initials'  => strtoupper(substr($amb->getConsolidatedFirstName(), 0, 1) . substr($amb->getLastName(), 0, 1)),
                'badge'     => $group ? ['label' => $group->getLetter(), 'color' => $group->getColor()] : null,
                'url'       => $this->generateUrl('app_ambassador_show', ['id' => $amb->getId()]),
            ];
        }

        foreach ($userRepo->searchByName($query) as $user) {
            $group = $user->getLetterGroup();
            $results[] = [
                'type'      => 'user',
                'name'      => $user->getConsolidatedFirstName() . ' ' . $user->getLastName(),
                'sub'       => $user->getPosition(),
                'photo'     => $user->getPhoto(),
                'initials'  => strtoupper(substr($user->getConsolidatedFirstName(), 0, 1) . substr($user->getLastName(), 0, 1)),
                'badge'     => $group ? ['label' => $group->getLetter(), 'color' => $group->getColor()] : null,
                'url'       => $this->generateUrl('app_user_show', ['id' => $user->getId()]),
            ];
        }

        return new JsonResponse(['people' => $results]);
    }
}
