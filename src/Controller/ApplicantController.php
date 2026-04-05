<?php

namespace App\Controller;

use App\Entity\Applicant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ApplicantRepository;

class ApplicantController extends AbstractController
{
    #[Route('/applicant', name: 'app_applicant_index')]
    public function index(ApplicantRepository $applicantRepository): Response
    {
        return $this->render('applicant/index.html.twig', [
            'controller_name' => 'ApplicantController',
            'applicants' => $applicantRepository->findAllOrderedByName(),
            'summary' => $applicantRepository->pullSummary(),
        ]);
    }
    
    #[Route('/applicant/{id}/do', name: 'app_applicant_edit', methods: ['GET', 'POST'])]
    public function applicantEditAction(Request $request, Applicant $applicant, ApplicantRepository $applicantRepository): Response
    {
        $editForm = $this->createForm('App\Form\ApplicantType', $applicant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $applicantRepository->save($applicant, true);

            return $this->redirectToRoute('app_applicant_index');
        }

        return $this->render('applicant/edit.html.twig', array(
            'applicant' => $applicant,
            'edit_form' => $editForm->createView(),
        ));
    }
    
}
