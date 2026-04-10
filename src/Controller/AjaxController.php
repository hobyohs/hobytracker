<?php

namespace App\Controller;

use App\Entity\ComingsAndGoings;
use App\Entity\User;
use App\Entity\Ambassador;
use App\Repository\AmbassadorRepository;
use App\Repository\ComingsAndGoingsRepository;
use App\Repository\UserRepository;
use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/ajax')]
class AjaxController extends AbstractController
{

    #[Route('/cg_update', name: 'cg_update', methods: ['POST'])]
    public function cgUpdate(Request $request, ComingsAndGoingsRepository $comingsAndGoingsRepository, EntityManagerInterface $entityManager)
    {
		$id = $request->request->get('cg_id');
		$field = $request->request->get('field');
		$status = $request->request->get('field_status');
					
		$obj = $comingsAndGoingsRepository->findOneBy(array('id' => $id));
		
		if ($field == "checked_in") {
			$obj->setCheckedIn($status);
			$obj->setCheckedInBy($this->getUser());
		} elseif ($field == "checked_out") {
			$obj->setCheckedOut($status);
			$obj->setCheckedOutBy($this->getUser());
		}
		
		$entityManager->flush();	
		
		$response = array("code" => 100, "success" => true);
		return new JsonResponse($response); 
		
    }
    
	
    #[Route('/bc_update', name: 'bc_update', methods: ['POST'])]
    public function bcUpdate(Request $request, AmbassadorRepository $ambassadorRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
		$id = $request->request->get('amb_id');
		$field = $request->request->get('field');
		$status = $request->request->get('field_status');
					
		$obj = $ambassadorRepository->findOneBy(array('id' => $id));
		
		if ($field == "bedFriday") {
			$obj->setBedFriday($status);
			$obj->setBedFridayUser($this->getUser());
		} elseif ($field == "bedSaturday") {
			$obj->setBedSaturday($status);
			$obj->setBedSaturdayUser($this->getUser());
		} elseif ($field == "bedThursday") {
			$obj->setBedThursday($status);
			$obj->setBedThursdayUser($this->getUser());
		}
		
		$entityManager->persist($obj);
		$entityManager->flush();	
		
		$response = array("code" => 100, "success" => true);
		return new JsonResponse($response); 
		
    }


	#[Route('/applicant_update', name: 'applicant_update', methods: ['POST'])]
    public function applicantUpdate(Request $request, ApplicantRepository $applicantRepository, EntityManagerInterface $entityManager)
    {
		$id = $request->request->get('applicant_id');
		$status = $request->request->get('field_status');
					
		$obj = $applicantRepository->findOneBy(array('id' => $id));
		
		$obj->setDecision($status);
		
		$entityManager->persist($obj);
		$entityManager->flush();		
		
		$response = array("code" => 100, "success" => true);
		return new JsonResponse($response); 
		
    }

	
	#[Route('/applicant_summary', name: 'applicant_summary', methods: ['POST'])]
	public function applicantSummary(Request $request, ApplicantRepository $applicantRepository)
	{
		return new JsonResponse($applicantRepository->pullSummary()); 
	}

	/**
	 * Toggle the C&G override flag for a given ambassador's check-in.
	 * State is held in session, not the DB — the actual ComingsAndGoings
	 * row is created on form submit if the override is on.
	 */
	#[Route('/checkin/{id}/toggle-cg-override', name: 'checkin_toggle_cg_override', methods: ['POST'])]
	public function toggleCgOverride(int $id, RequestStack $requestStack)
	{
		$session = $requestStack->getSession();
		$key = "checkin_override_{$id}_cg";
		$current = $session->get($key, false);
		$new = !$current;
		$session->set($key, $new);

		return new JsonResponse(['success' => true, 'state' => $new ? 'on' : 'off']);
	}

	/**
	 * Toggle the meds override flag for a given ambassador's check-in.
	 * State is held in session, not the DB — checkinMeds is set to 1
	 * on form submit if the override is on.
	 */
	#[Route('/checkin/{id}/toggle-meds-override', name: 'checkin_toggle_meds_override', methods: ['POST'])]
	public function toggleMedsOverride(int $id, RequestStack $requestStack)
	{
		$session = $requestStack->getSession();
		$key = "checkin_override_{$id}_meds";
		$current = $session->get($key, false);
		$new = !$current;
		$session->set($key, $new);

		return new JsonResponse(['success' => true, 'state' => $new ? 'on' : 'off']);
	}

}