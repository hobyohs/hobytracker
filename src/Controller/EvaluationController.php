<?php

namespace App\Controller;

use App\Entity\AmbassadorEvaluation;
use App\Entity\StaffEvaluation;
use App\Entity\Ambassador;
use App\Entity\LetterGroup;
use App\Entity\StaffAssignment;
use App\Form\AmbassadorEvaluationFormType;
use App\Form\StaffEvaluationFormType;
use App\Repository\AmbassadorEvaluationRepository;
use App\Repository\StaffEvaluationRepository;
use App\Service\SeminarYearService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class EvaluationController extends AbstractController
{
    public function __construct(
        private SeminarYearService $seminarYearService,
        private EntityManagerInterface $em,
    ) {}

    // ─────────────────────────────────────────────────────────
    // AMBASSADOR EVALUATIONS
    // ─────────────────────────────────────────────────────────

    #[Route('/evaluations/ambassadors/{letter}', name: 'ambeval_index', methods: ['GET'])]
    public function ambassadorEvalIndex(
        LetterGroup $letterGroup,
        AmbassadorEvaluationRepository $evalRepo,
    ): Response {
        $currentAssignment = $this->getUser()->getActiveAssignment();

        if ($currentAssignment?->getLetterGroup() !== $letterGroup) {
            $this->addFlash('error', 'You are not authorized to view this page.');
            return $this->render('default/flash.html.twig');
        }

        $year = $this->seminarYearService->getActiveSeminarYear();

        // Build a map of ambassador_id => AmbassadorEvaluation for quick lookup in template
        $existingEvals = $evalRepo->findByYear($year);
        $evalMap = [];
        foreach ($existingEvals as $eval) {
            if ($eval->getAmbassador()?->getLetterGroup() === $letterGroup) {
                $evalMap[$eval->getAmbassador()->getId()] = $eval;
            }
        }

        return $this->render('evaluations/ambassadors.html.twig', [
            'group'    => $letterGroup,
            'evalMap'  => $evalMap,
            'evalOpen' => $this->seminarYearService->isEvalPeriodOpen(),
        ]);
    }

    #[Route('/evaluations/ambassadors/{id}/edit', name: 'ambeval_form', methods: ['GET', 'POST'])]
    public function ambassadorEvalForm(
        Request $request,
        Ambassador $ambassador,
        AmbassadorEvaluationRepository $evalRepo,
    ): Response {
        $currentAssignment = $this->getUser()->getActiveAssignment();

        if ($currentAssignment?->getLetterGroup() !== $ambassador->getLetterGroup()) {
            $this->addFlash('error', 'You are not authorized to evaluate this ambassador.');
            return $this->render('default/flash.html.twig');
        }

        $year = $this->seminarYearService->getActiveSeminarYear();

        // Find existing eval for this ambassador+year, or create a new one
        $eval = $this->em->getRepository(AmbassadorEvaluation::class)->findOneBy([
            'ambassador'  => $ambassador,
            'seminarYear' => $year,
        ]);

        if (!$eval) {
            $eval = new AmbassadorEvaluation();
            $eval->setAmbassador($ambassador);
            $eval->setSeminarYear($year);
        }

        // Submitted evals are read-only
        if ($eval->isSubmitted()) {
            return $this->render('evaluations/edit_ambassador.html.twig', [
                'ambassador' => $ambassador,
                'eval'       => $eval,
                'form'       => null,
                'evalOpen'   => $this->seminarYearService->isEvalPeriodOpen(),
            ]);
        }

        $form = $this->createForm(AmbassadorEvaluationFormType::class, $eval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $request->request->get('_submit_action', 'draft');

            if ($action === 'submit') {
                $eval->setStatus('submitted');
                $eval->setSubmittedAt(new \DateTime());
                $eval->setSubmittedBy($currentAssignment);
            } else {
                $eval->setStatus('draft');
            }

            $this->em->persist($eval);
            $this->em->flush();

            $this->addFlash('success', $action === 'submit'
                ? "Evaluation for {$ambassador->getConsolidatedFirstName()} submitted."
                : "Draft saved for {$ambassador->getConsolidatedFirstName()}."
            );

            return $this->redirectToRoute('ambeval_index', [
                'letter' => $ambassador->getLetterGroup()->getLetter(),
            ]);
        }

        return $this->render('evaluations/edit_ambassador.html.twig', [
            'ambassador' => $ambassador,
            'eval'       => $eval,
            'form'       => $form,
            'evalOpen'   => $this->seminarYearService->isEvalPeriodOpen(),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // STAFF EVALUATIONS
    // ─────────────────────────────────────────────────────────

    #[Route('/evaluations/staff/{letter}', name: 'staffeval_index', methods: ['GET'])]
    public function staffEvalIndex(
        LetterGroup $letterGroup,
        StaffEvaluationRepository $evalRepo,
    ): Response {
        $currentAssignment = $this->getUser()->getActiveAssignment();

        if ($currentAssignment?->getLetterGroup() !== $letterGroup) {
            $this->addFlash('error', 'You are not authorized to view this page.');
            return $this->render('default/flash.html.twig');
        }

        $year = $this->seminarYearService->getActiveSeminarYear();

        // All evals written BY the current user this year, keyed by subject_id
        $myEvals = $evalRepo->findByEvaluator($currentAssignment->getId());
        $evalMap = [];
        foreach ($myEvals as $eval) {
            if ($eval->getSeminarYear() === $year) {
                $evalMap[$eval->getSubject()->getId()] = $eval;
            }
        }

        return $this->render('evaluations/staff.html.twig', [
            'group'      => $letterGroup,
            'evalMap'    => $evalMap,
            'evalOpen'   => $this->seminarYearService->isEvalPeriodOpen(),
            'myAssignId' => $currentAssignment->getId(),
        ]);
    }

    #[Route('/evaluations/staff/{id}/edit', name: 'staffeval_form', methods: ['GET', 'POST'])]
    public function staffEvalForm(
        Request $request,
        StaffAssignment $staffAssignment,
        StaffEvaluationRepository $evalRepo,
    ): Response {
        $currentAssignment = $this->getUser()->getActiveAssignment();

        // Self-eval prevention
        if ($currentAssignment?->getId() === $staffAssignment->getId()) {
            $this->addFlash('error', 'You cannot evaluate yourself.');
            return $this->redirectToRoute('staffeval_index', [
                'letter' => $staffAssignment->getLetterGroup()->getLetter(),
            ]);
        }

        if ($currentAssignment?->getLetterGroup() !== $staffAssignment->getLetterGroup()) {
            $this->addFlash('error', 'You are not authorized to evaluate this staff member.');
            return $this->render('default/flash.html.twig');
        }

        $year = $this->seminarYearService->getActiveSeminarYear();

        // Find existing eval for (evaluator, subject, year) or create new
        $eval = $this->em->getRepository(StaffEvaluation::class)->findOneBy([
            'subject'     => $staffAssignment,
            'evaluator'   => $currentAssignment,
            'seminarYear' => $year,
        ]);

        if (!$eval) {
            $eval = new StaffEvaluation();
            $eval->setSubject($staffAssignment);
            $eval->setEvaluator($currentAssignment);
            $eval->setSeminarYear($year);
        }

        // Submitted evals are read-only
        if ($eval->isSubmitted()) {
            return $this->render('evaluations/edit_staff.html.twig', [
                'subject'  => $staffAssignment,
                'eval'     => $eval,
                'form'     => null,
                'evalOpen' => $this->seminarYearService->isEvalPeriodOpen(),
            ]);
        }

        $form = $this->createForm(StaffEvaluationFormType::class, $eval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $request->request->get('_submit_action', 'draft');

            if ($action === 'submit') {
                $eval->setStatus('submitted');
                $eval->setSubmittedAt(new \DateTime());
            } else {
                $eval->setStatus('draft');
            }

            $this->em->persist($eval);
            $this->em->flush();

            $this->addFlash('success', $action === 'submit'
                ? "Evaluation for {$staffAssignment->getConsolidatedFirstName()} submitted."
                : "Draft saved for {$staffAssignment->getConsolidatedFirstName()}."
            );

            return $this->redirectToRoute('staffeval_index', [
                'letter' => $staffAssignment->getLetterGroup()->getLetter(),
            ]);
        }

        return $this->render('evaluations/edit_staff.html.twig', [
            'subject'  => $staffAssignment,
            'eval'     => $eval,
            'form'     => $form,
            'evalOpen' => $this->seminarYearService->isEvalPeriodOpen(),
        ]);
    }
}
