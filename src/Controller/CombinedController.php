<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ambassador;
use App\Entity\User;
//use App\Form\AmbassadorType;
use App\Repository\AmbassadorRepository;
use App\Repository\UserRepository;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lists')]
class CombinedController extends AbstractController
{
    
    #[Route('/all_groups', name: 'all_groups', methods: ['GET'])]
    public function groupAction(AmbassadorRepository $ambassadorRepository, UserRepository $userRepository)
    {
    
        $ambassadors = $ambassadorRepository->findAllWithGroups();
        $users = $userRepository->findAllWithGroups();
        
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
                'sort' => 4
            ];
        }
        
        foreach ($users as $user) {
            
            $people[] = [
                'type' => 'user',
                'showpath' => 'app_user_show',
                'id' => $user->getId(),
                'lastName' => $user->getLastName(),
                'firstName' => $user->getConsolidatedFirstName(),
                'group' => $user->getLetterGroup(),
                'school' => '',
                'role' => $user->getPosition(),
                'sort' => $user->getSortRank()
            ];
            
            unset($sort);
            
        }
    
        return $this->render('combined/letterGroups.html.twig', array(
            'people' => $people
        ));
    }
    
    #[Route('/emergency', name: 'emergency_contacts', methods: ['GET'])]
    public function ecAction(AmbassadorRepository $ambassadorRepository, UserRepository $userRepository)
    {
    
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $users = $userRepository->findAllCombinedInfo();
        
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
                'ecPhone2' => $ambassador->getEcPhone2()
            ];
        }
        
        foreach ($users as $user) {
            $people[] = [
                'type' => 'user',
                'showpath' => 'app_user_show',
                'id' => $user->getId(),
                'lastName' => $user->getLastName(),
                'firstName' => $user->getConsolidatedFirstName(),
                'prefName' => $user->getPrefName(),
                'group' => 'Staff',
                'ecFirstName' => $user->getEcFirstName(),
                'ecLastName' => $user->getEcLastName(),
                'ecRelationship' => $user->getEcRelationship(),
                'ecPhone1' => $user->getEcPhone1(),
                'ecPhone2' => $user->getEcPhone2()
            ];
        }
    
        return $this->render('combined/ec.html.twig', array(
            'people' => $people
        ));
    }
    
    #[Route('/dorms', name: 'dorm_list', methods: ['GET'])]
    public function dormAction(AmbassadorRepository $ambassadorRepository, UserRepository $userRepository)
    {
    
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $users = $userRepository->findAllCombinedInfo();
        
        $people = [];
        
        foreach ($ambassadors as $ambassador) {
            if (!empty($ambassador->getDorm()) and !empty($ambassador->getRoom())) {
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
        
        foreach ($users as $user) {
            
            if (!empty($user->getDorm()) and !empty($user->getRoom())) {
                $people[] = [
                    'type' => 'user',
                    'showpath' => 'app_user_show',
                    'id' => $user->getId(),
                    'lastName' => $user->getLastName(),
                    'firstName' => $user->getConsolidatedFirstName(),
                    'shirtSize' => $user->getShirtSize(),
                    'dormRoom' => $user->getDormRoom(),
                    'group' => 'Staff',
                    'dorm' => $user->getDorm(),
                    'room' => $user->getRoom(),
                ];
             }
             
        }
    
        return $this->render('combined/dorms.html.twig', array(
            'people' => $people
        ));
    }
    
    #[Route('/dietary', name: 'dietary', methods: ['GET'])]
    public function dietAction(AmbassadorRepository $ambassadorRepository, UserRepository $userRepository)
    {
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $users = $userRepository->findAllCombinedInfo();
        
        $people = [];
    
        $nullDietRestrictions = [
            "0",
            "I Eat Everything, no restrictions",
            "I Eat Everything no restrictions;",
            "I Eat Everything, no restrictions;",
            "None",
            "I Eat Everything, no restrictions,"
        ];
        
        foreach ($ambassadors as $ambassador) {
    
            if(!in_array($ambassador->getDietRestrictions(), $nullDietRestrictions)) {
                $restrictions = $ambassador->getDietRestrictions();
            } else {
                $restrictions = NULL;
            }
            if (!empty($restrictions) OR !empty($ambassador->getDietInfo()) OR !empty($ambassador->getDietSeverity())) {
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
            unset($restrictions);
        }
        
        foreach ($users as $user) {
            
            if(!in_array($user->getDietRestrictions(), $nullDietRestrictions)) {
                $restrictions = $user->getDietRestrictions();
            } else {
                $restrictions = NULL;
            }
            
            if (!empty($restrictions) OR !empty($user->getDietInfo()) OR !empty($user->getDietSeverity())) {
            
                $people[] = [
                    'type' => 'user',
                    'showpath' => 'app_user_show',
                    'id' => $user->getId(),
                    'lastName' => $user->getLastName(),
                    'firstName' => $user->getConsolidatedFirstName(),
                    'group' => 'Staff',
                    'dietRestrictions' => $restrictions,
                    'dietInfo' => $user->getDietInfo(),
                    'dietSeverity' => $user->getDietSeverity(),
                ];
            }
            unset($restrictions);
        }
        return $this->render('combined/dietary.html.twig', array(
            'people' => $people
        ));
    }
    
    #[Route('/medical', name: 'medical', methods: ['GET'])]
    public function medicalAction(AmbassadorRepository $ambassadorRepository, UserRepository $userRepository)
    {
        
        $ambassadors = $ambassadorRepository->findAllCombinedInfo();
        $users = $userRepository->findAllCombinedInfo();
        
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
        
        foreach ($users as $user) {
            
            $people[] = [
                'type' => 'user',
                'showpath' => 'app_user_show',
                'id' => $user->getId(),
                'lastName' => $user->getLastName(),
                'firstName' => $user->getConsolidatedFirstName(),
                'group' => 'Staff',
                'currentConditions' => $user->getCurrentConditions(),
                'exerciseLimits' => $user->getExerciseLimits(),
                'allergies' => $user->getAllergies(),
                'medAllergies' => $user->getMedAllergies(),
                'currentRx' => $user->getCurrentRx(),
            ];
                
        }
        return $this->render('combined/medical.html.twig', array(
            'people' => $people
        ));
    }
    
}
