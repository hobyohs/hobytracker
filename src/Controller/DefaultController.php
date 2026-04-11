<?php

namespace App\Controller;

use App\Repository\AmbassadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/program', name: 'program_index', methods: ['GET'])]
    public function programIndex(): Response
    {
        return $this->render('default/program.html.twig');
    }

    public function index(AmbassadorRepository $ambassadorRepository): Response
    {
        $data = [
            'controller_name' => 'DefaultController',
        ];

        if ($this->isGranted('ROLE_BOARD')) {
            $totalAmb = $ambassadorRepository->countAll();
            $data['totalAmb']          = $totalAmb;
            $data['totalCalled']       = $ambassadorRepository->countJuniorCalled();
            $data['totalCallSuccess']  = $ambassadorRepository->countJuniorCallSuccess();
            $data['totalPsm']          = $ambassadorRepository->countPsmReturned();
            $data['callPct']           = $totalAmb > 0 ? round(($data['totalCalled']  / $totalAmb) * 100) : 0;
            $data['psmPct']            = $totalAmb > 0 ? round(($data['totalPsm']     / $totalAmb) * 100) : 0;
        }

        return $this->render('default/index.html.twig', $data);
    }
}

