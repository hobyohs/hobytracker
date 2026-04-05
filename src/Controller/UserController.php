<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\LetterGroup;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAllOrderedByName(),
        ]);
    }
    
    #[Route('/faces', name: 'app_user_faces', methods: ['GET'])]
    public function facesAction(UserRepository $userRepository): Response
    {    
        return $this->render('user/faces.html.twig', array(
            'users' => $userRepository->findAllOrderedByName(),
        ));
    }
    
    #[Route('/requirements/{id}', name: 'app_user_edit_requirements', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_BOARD')]
    public function requirementsEditAction(Request $request, User $user, UserRepository $userRepository): Response
    {    
        $form = $this->createForm('App\Form\UserRequirementsType', $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
        
            return $this->redirectToRoute('app_user_requirements', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('user/edit_requirements.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/requirements', name: 'app_user_requirements', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function requirementsAction(UserRepository $userRepository): Response
    {    
        
        $lastUpdated = $userRepository->getPsmsLastUpdated();
        return $this->render('user/requirements.html.twig', array(
            'users' => $userRepository->findAllOrderedByName(),
            'lastUpdated' => $lastUpdated
        ));
    }
    
    #[Route('/assignments/checkin', name: 'app_user_checkin_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function checkinAssignmentsAction(UserRepository $userRepository): Response
    {    
        return $this->render('user/checkin_assignments.html.twig', array(
            'users' => $userRepository->findAllOrderedByName()
        ));
    }
    
    #[Route('/assignments/closingceremony', name: 'app_user_cc_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function ccAssignmentsAction(UserRepository $userRepository): Response
    {    
        return $this->render('user/cc_assignments.html.twig', array(
            'users' => $userRepository->findAllOrderedByName()
        ));
    }
    
    #[Route('/assignments/checkout', name: 'app_user_checkout_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function checkoutAssignmentsAction(UserRepository $userRepository): Response
    {    
        return $this->render('user/checkout_assignments.html.twig', array(
            'users' => $userRepository->findAllOrderedByName()
        ));
    }
    
    #[Route('/assignments/my', name: 'app_user_my_assignments', methods: ['GET'])]
    #[IsGranted('ROLE_BOARD')]
    public function myAssignmentsAction(UserRepository $userRepository): Response
    {    
        return $this->render('user/my_assignments.html.twig', array(
            'user' => $this->getUser()
        ));
    }
    
    #[Route('/evaluations/{letter}', name: 'staffeval_index', methods: ['GET'])]
    public function staffEvalIndex(LetterGroup $letterGroup): Response
    {
        if ($this->getUser()->getLetterGroup() == $letterGroup) {
            return $this->render('evaluations/staff.html.twig', array(
                'group' => $letterGroup,
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
    
    #[Route('/evaluations/{id}/do', name: 'staffeval_edit', methods: ['GET', 'POST'])]
    public function staffEvalEditAction(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->getUser()->getLetterGroup() == $user->getLetterGroup()) {
            $editForm = $this->createForm('App\Form\StaffEvaluationType', $user);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $user->setEvalStatus(true);
                $userRepository->save($user, true);
    
                return $this->redirectToRoute('staffeval_index', array('letter' => $this->getUser()->getLetterGroup()->getLetter()));
            }
    
            return $this->render('evaluations/edit_staff.html.twig', array(
                'user' => $user,
                'edit_form' => $editForm->createView(),
            ));
        }
        else {
            $this->addFlash(
                'error',
                'You are not authorized to view this page. If you feel this is in error, please contact the Director of Facilitators.'
            );
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
