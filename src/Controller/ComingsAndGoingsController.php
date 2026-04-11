<?php

namespace App\Controller;

use App\Entity\ComingsAndGoings;
use App\Entity\Ambassador;
use App\Form\ComingsAndGoingsType;
use App\Repository\ComingsAndGoingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comingsandgoings')]
class ComingsAndGoingsController extends AbstractController
{
    #[Route('/', name: 'app_comings_and_goings_index', methods: ['GET'])]
    public function index(ComingsAndGoingsRepository $comingsAndGoingsRepository): Response
    {
        return $this->render('comings_and_goings/index.html.twig', [
            'comings_and_goings' => $comingsAndGoingsRepository->findAllActive(),
        ]);
    }
    
    #[Route('/new', name: 'app_comings_and_goings_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_BOARD')]
    public function newAction(Request $request, ComingsAndGoingsRepository $comingsAndGoingsRepository, EntityManagerInterface $entityManager): Response
    {
        $comingsAndGoing = new ComingsAndGoings();

        $ambassadorId = $request->query->get('ambassador_id');
        if ($ambassadorId) {
            $ambassador = $entityManager->getRepository(Ambassador::class)->find($ambassadorId);
            if ($ambassador) {
                $comingsAndGoing->setAmbassador($ambassador);
            }
        }

        $form = $this->createForm('App\Form\ComingsAndGoingsType', $comingsAndGoing);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $comingsAndGoing->setSeminarYear($comingsAndGoing->getAmbassador()->getSeminarYear());
            $entityManager->persist($comingsAndGoing);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_comings_and_goings_index');
        }
        
        return $this->render('comings_and_goings/new.html.twig', array(
            'comingsAndGoing' => $comingsAndGoing,
            'form' => $form->createView(),
        ));
        
    }

    #[Route('/{id}', name: 'app_comings_and_goings_show', methods: ['GET'])]
    public function show(ComingsAndGoings $comingsAndGoing): Response
    {
        
        return $this->render('comings_and_goings/show.html.twig', [
            'comings_and_going' => $comingsAndGoing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comings_and_goings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ComingsAndGoings $comingsAndGoing, ComingsAndGoingsRepository $comingsAndGoingsRepository): Response
    {
        $form = $this->createForm(ComingsAndGoingsType::class, $comingsAndGoing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comingsAndGoingsRepository->save($comingsAndGoing, true);

            return $this->redirectToRoute('app_comings_and_goings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comings_and_goings/edit.html.twig', [
            'comings_and_going' => $comingsAndGoing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/deactivate', name: 'app_comings_and_goings_deactivate', methods: ['POST'])]
    #[IsGranted('ROLE_BOARD')]
    public function deactivate(ComingsAndGoings $comingsAndGoing, EntityManagerInterface $entityManager): JsonResponse
    {
        $comingsAndGoing->setActive(false);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

}
