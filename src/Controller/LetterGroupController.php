<?php

namespace App\Controller;

use App\Entity\LetterGroup;
use App\Form\LetterGroupType;
use App\Repository\LetterGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groups')]
class LetterGroupController extends AbstractController
{
    #[Route('/', name: 'app_letter_group_index', methods: ['GET'])]
    public function index(LetterGroupRepository $letterGroupRepository): Response
    {
        return $this->render('letter_group/index.html.twig', [
            'groups' => $letterGroupRepository->findAll(),
        ]);
    }
    
    #[Route('/interviews', name: 'app_letter_group_interviews', methods: ['GET'])]
    public function interviews(LetterGroupRepository $letterGroupRepository): Response
    {
        return $this->render('letter_group/interviews.html.twig', [
            'groups' => $letterGroupRepository->findAll(),
        ]);
    }
    
    #[Route('/{letter}/faces', name: 'app_letter_group_show_faces', methods: ['GET'])]
    public function showFaceAction(LetterGroup $letterGroup): Response
    {
        return $this->render('letter_group/faces.html.twig', [
            'group' => $letterGroup,
        ]);
    }
    
    #[Route('/{letter}/thankyous', name: 'app_letter_group_show_thankyous', methods: ['GET'])]
    public function showThankYouAction(LetterGroup $group)
    {
        return $this->render('letter_group/thankyous.html.twig', array(
            'group' => $group,
        ));
    }
    
    #[Route('/{letter}/calls', name: 'app_letter_group_calls', methods: ['GET'])]
    public function showCallsAction(LetterGroup $group)
    {
        
        if ($this->getUser()->getLetterGroup() == $group) {
            return $this->render('letter_group/calls.html.twig', array(
                'group' => $group,
            ));
        }
        
        else {
            $this->addFlash(
                'error',
                'You are not authorized to view this page. If you think this is in error, please contact the Director of Facilitators.'
            );
            return $this->render('default/flash.html.twig');
        }

    }
    
    #[Route('/{letter}', name: 'app_letter_group_show', methods: ['GET'])]
    public function show(LetterGroup $letterGroup): Response
    {
        return $this->render('letter_group/show.html.twig', [
            'group' => $letterGroup,
        ]);
    }
}
