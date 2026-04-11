<?php

namespace App\Controller;

use App\Entity\Ambassador;
use App\Entity\ComingsAndGoings;
use App\Entity\User;
use App\Form\AmbassadorType;
use App\Repository\AmbassadorRepository;
use App\Repository\DormRoomRepository;
use App\Entity\LetterGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/ambassador')]
class AmbassadorController extends AbstractController
{
    #[Route('/', name: 'app_ambassador_index', methods: ['GET'])]
    public function index(AmbassadorRepository $ambassadorRepository): Response
    {
        return $this->render('ambassador/index.html.twig', [
            'ambassadors' => $ambassadorRepository->findAll(),
        ]);
    }
    
    #[Route('/psm', name: 'app_ambassador_psm', methods: ['GET'])]
    public function psmAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findAllOrderedByName();
        $lastUpdated = $ambassadorRepository->getPsmsLastUpdated();
        $numreturn = $ambassadorRepository->countPsmReturned();
        $totalamb = $ambassadorRepository->countAll();
        return $this->render('ambassador/psm.html.twig', array(
            'ambassadors' => $ambassadors,
            'lastUpdated' => $lastUpdated,
            'numreturn' => $numreturn,
            'totalamb' => $totalamb,
        ));
    }
    
    #[Route('/calls', name: 'app_ambassador_calls', methods: ['GET'])]
    public function callsAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findAllOrderedByName();
        $totalCalled = $ambassadorRepository->countJuniorCalled();
        $totalSuccess = $ambassadorRepository->countJuniorCallSuccess();
        $totalamb = $ambassadorRepository->countAll();
        return $this->render('ambassador/preseminarcalls.html.twig', array(
            'ambassadors' => $ambassadors,
            'totalCalled' => $totalCalled,
            'totalSuccess' => $totalSuccess,
            'totalAmb' => $totalamb
        ));
    }
    
    #[Route('/noshows', name: 'app_ambassador_noshows', methods: ['GET'])]
    public function noShowsAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findCheckinList();
    
        return $this->render('ambassador/noshows.html.twig', array(
            'ambassadors' => $ambassadors,
        ));
    }
    
    #[Route('/bus', name: 'app_ambassador_bus', methods: ['GET'])]
    public function busAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findThoseTakingBus();
    
        return $this->render('ambassador/bus.html.twig', array(
            'ambassadors' => $ambassadors,
        ));
    }
    
    #[Route('/calls/{id}', name: 'app_ambassador_call_form', methods: ['GET', 'POST'])]
    public function callsFormAction(Request $request, Ambassador $ambassador, EntityManagerInterface $entityManager): Response
    {
        $editForm = $this->createForm('App\Form\CallType', $ambassador);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->persist($ambassador);
            $entityManager->flush();
            return $this->redirectToRoute('app_letter_group_calls', array('letter' => $this->getUser()->getLetterGroup()->getLetter()));
        }
        
        if (count($ambassador->getActiveComingsAndGoings()) > 0) {
            $cg_status = TRUE;
        } else {
            $cg_status = FALSE;
        }        
        
        if ($ambassador->isCheckinPaperwork()) $psm_status = TRUE;
        else $psm_status = FALSE;  

        return $this->render('ambassador/call.html.twig', array(
            'ambassador' => $ambassador,
            'edit_form' => $editForm->createView(),
            'cg_status' => $cg_status,
            'psm_status' => $psm_status
        ));
    }
    
    #[Route('/checkin', name: 'app_ambassador_checkin', methods: ['GET'])]
    public function checkinAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findCheckinList();
        $checkedin = $ambassadorRepository->countCheckedIn();
        $totalamb = $ambassadorRepository->countAll();
    
        return $this->render('ambassador/checkin/index.html.twig', array(
            'ambassadors' => $ambassadors,
            'checkedin' => $checkedin,
            'totalamb' => $totalamb
        ));
    }
    
    #[Route('/checkin/keydeposits', name: 'app_ambassador_keydeposits', methods: ['GET'])]
    public function keyDepositAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findAllOrderedByName();
    
        return $this->render('ambassador/checkin/keydeposits.html.twig', array(
            'ambassadors' => $ambassadors,
        ));
    }
    
    #[Route('/checkin/{id}', name: 'app_ambassador_checkin_form', methods: ['GET', 'POST'])]
    public function checkinFormAction(Request $request, Ambassador $ambassador, EntityManagerInterface $entityManager, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $cgOverrideKey = "checkin_override_{$ambassador->getId()}_cg";
        $medsOverrideKey = "checkin_override_{$ambassador->getId()}_meds";

        $editForm = $this->createForm('App\Form\CheckinType', $ambassador);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Apply session overrides before persisting
            if ($session->get($cgOverrideKey, false)) {
                $cg = new ComingsAndGoings();
                $cg->setAmbassador($ambassador);
                $cg->setSeminarYear($ambassador->getSeminarYear());
                $entityManager->persist($cg);
            }
            if ($session->get($medsOverrideKey, false)) {
                $ambassador->setCheckinMeds(!$ambassador->isCheckinMeds());
            }

            $entityManager->persist($ambassador);
            $entityManager->flush();

            // Clear session overrides for this ambassador now that they're persisted
            $session->remove($cgOverrideKey);
            $session->remove($medsOverrideKey);

            return $this->redirectToRoute('app_ambassador_checkin');
        }
        
        if (count($ambassador->getActiveComingsAndGoings()) > 0) {
            $cg_status = TRUE;
            if ($ambassador->isCgForm()) {
                $cgform_status = TRUE;  
            } else {
                $cgform_status = FALSE;
            }
        } else {
            $cg_status = FALSE;
            $cgform_status = TRUE;
        }        
        
        if ($ambassador->isCheckinPaperwork()) $formstack_status = TRUE;
        else $formstack_status = FALSE;  
        
        
        if ($formstack_status AND $cgform_status) {
            $psm_status = TRUE;
        } else {
            $psm_status = FALSE;
        }
                    
        if ($ambassador->isCheckinDeposit()) $deposit_status = TRUE;
        else $deposit_status = FALSE;
        
        if ($ambassador->isCheckinMeds()) $meds_status = TRUE;
        else $meds_status = FALSE;

        return $this->render('ambassador/checkin/form.html.twig', array(
            'ambassador' => $ambassador,
            'edit_form' => $editForm->createView(),
            'cg_status' => $cg_status,
            'psm_status' => $psm_status,
            'deposit_status' => $deposit_status,
            'meds_status' => $meds_status,
            'cg_override' => $session->get($cgOverrideKey, false),
            'meds_override' => $session->get($medsOverrideKey, false),
        ));
    }
    
    #[Route('/checkout', name: 'app_ambassador_checkout', methods: ['GET'])]
    public function checkoutAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findCheckoutList();
        $checkedout = $ambassadorRepository->countCheckedOut();
        $totalamb = $ambassadorRepository->countAll();
    
        return $this->render('ambassador/checkout/index.html.twig', array(
            'ambassadors' => $ambassadors,
            'checkedout' => $checkedout,
            'totalamb' => $totalamb
        ));
    }
        
    #[Route('/checkout/{id}', name: 'app_ambassador_checkout_form', methods: ['GET', 'POST'])]
    public function checkoutFormAction(Request $request, Ambassador $ambassador, EntityManagerInterface $entityManager): Response
    {
        $editForm = $this->createForm('App\Form\CheckoutType', $ambassador);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->persist($ambassador);
            $entityManager->flush();
            return $this->redirectToRoute('app_ambassador_checkout');
        }
        
        return $this->render('ambassador/checkout/form.html.twig', array(
            'ambassador' => $ambassador,
            'edit_form' => $editForm->createView(),
            'meds_status' => $ambassador->isCheckinMeds(),
        ));
    }
    
    #[Route('/faces', name: 'app_ambassador_faces', methods: ['GET'])]
    public function facesAction(AmbassadorRepository $ambassadorRepository): Response
    {
        $ambassadors = $ambassadorRepository->findAllOrderedByName();
    
        return $this->render('ambassador/faces.html.twig', array(
            'ambassadors' => $ambassadors,
        ));
    }
    
    #[Route('/thankyous', name: 'app_ambassador_thankyous', methods: ['GET'])]
    public function thankYousAction(AmbassadorRepository $ambassadorRepository): Response
    {

        return $this->render('ambassador/thankyous.html.twig', array(
            'ambassadors' => $ambassadorRepository->findAllNotCheckedOutOrderedByName()
        ));
    }
    
    #[Route('/bedchecks', name: 'app_ambassador_bedchecks', methods: ['GET'])]
    public function bedChecksAction(DormRoomRepository $dormRoomRepository, Request $request): Response
    {
        [$nightField, $nightName] = $this->resolveNight($request);
        $allRooms = $dormRoomRepository->findAllOrderedForBedChecks();
        $now = new \DateTime('now', new \DateTimeZone('America/New_York'));

        $floors = [];
        foreach ($allRooms as $room) {
            $ambassadors = $room->getAmbassadors();
            if ($ambassadors->isEmpty()) continue;

            $key = $room->getDorm() . '|||' . $room->getFloor();
            if (!isset($floors[$key])) {
                $floors[$key] = [
                    'dorm'           => $room->getDorm(),
                    'floor'          => $room->getFloor(),
                    'totalRooms'     => 0,
                    'confirmedRooms' => 0,
                    'totalAmbs'      => 0,
                    'confirmedAmbs'  => 0,
                ];
            }

            $floors[$key]['totalRooms']++;
            $roomConfirmed = true;
            foreach ($ambassadors as $amb) {
                $isAway = $this->isAmbassadorAway($amb, $now);
                if ($isAway) continue;
                $floors[$key]['totalAmbs']++;
                $confirmed = $this->getNightConfirmed($amb, $nightField);
                if ($confirmed) {
                    $floors[$key]['confirmedAmbs']++;
                } else {
                    $roomConfirmed = false;
                }
            }
            if ($roomConfirmed) $floors[$key]['confirmedRooms']++;
        }

        return $this->render('ambassador/bedchecks.html.twig', [
            'floors'     => array_values($floors),
            'nightField' => $nightField,
            'nightName'  => $nightName,
        ]);
    }

    #[Route('/bedchecks/{dorm}/{floor}', name: 'app_ambassador_bedchecks_floor', methods: ['GET'])]
    public function bedChecksFloorAction(string $dorm, string $floor, DormRoomRepository $dormRoomRepository, Request $request): Response
    {
        [$nightField, $nightName] = $this->resolveNight($request);
        $allRooms = $dormRoomRepository->findAllOrderedForBedChecks();
        $now = new \DateTime('now', new \DateTimeZone('America/New_York'));

        $rooms = [];
        $totalAmbs = 0;
        $confirmedAmbs = 0;

        foreach ($allRooms as $room) {
            if ($room->getDorm() !== $dorm || $room->getFloor() !== $floor) continue;
            $ambassadors = $room->getAmbassadors();
            if ($ambassadors->isEmpty()) continue;

            $occupants = [];
            $roomConfirmed = true;
            foreach ($ambassadors as $amb) {
                $isAway      = $this->isAmbassadorAway($amb, $now);
                $confirmed   = $this->getNightConfirmed($amb, $nightField);
                $confirmedBy = $this->getNightConfirmedBy($amb, $nightField);
                $occupants[] = [
                    'ambassador'  => $amb,
                    'isAway'      => $isAway,
                    'awayReturn'  => $isAway ? $this->getAmbassadorAwayReturn($amb, $now) : null,
                    'confirmed'   => $confirmed,
                    'confirmedBy' => $confirmedBy,
                ];
                if (!$isAway) {
                    $totalAmbs++;
                    if ($confirmed) { $confirmedAmbs++; } else { $roomConfirmed = false; }
                }
            }

            $allAway = !array_filter($occupants, fn($o) => !$o['isAway']);

            $rooms[] = [
                'dormRoom'      => $room,
                'occupants'     => $occupants,
                'roomConfirmed' => $roomConfirmed && !$allAway,
                'allAway'       => $allAway,
            ];
        }

        return $this->render('ambassador/bedchecks_floor.html.twig', [
            'dorm'          => $dorm,
            'floor'         => $floor,
            'rooms'         => $rooms,
            'nightField'    => $nightField,
            'nightName'     => $nightName,
            'totalAmbs'     => $totalAmbs,
            'confirmedAmbs' => $confirmedAmbs,
        ]);
    }

    private function resolveNight(Request $request): array
    {
        $override = $request->query->get('night');
        if ($override && in_array($override, ['Thursday', 'Friday', 'Saturday'])) {
            $nightName = $override;
        } else {
            $now = new \DateTime('now', new \DateTimeZone('America/New_York'));
            if ((int)$now->format('G') < 4) {
                $now->modify('-1 day');
            }
            $day = $now->format('l');
            $nightName = in_array($day, ['Thursday', 'Friday', 'Saturday']) ? $day : 'Thursday';
        }
        $nightField = 'bed' . $nightName;
        return [$nightField, $nightName];
    }

    private function isAmbassadorAway(Ambassador $ambassador, \DateTime $now): bool
    {
        foreach ($ambassador->getActiveComingsAndGoings() as $cg) {
            if ($cg->getDeparture() !== null && $cg->getDeparture() < $now) {
                if ($cg->getArrival() === null || $cg->getArrival() > $now) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getAmbassadorAwayReturn(Ambassador $ambassador, \DateTime $now): ?\DateTimeInterface
    {
        foreach ($ambassador->getActiveComingsAndGoings() as $cg) {
            if ($cg->getDeparture() !== null && $cg->getDeparture() < $now) {
                if ($cg->getArrival() === null || $cg->getArrival() > $now) {
                    return $cg->getArrival(); // null = no return expected
                }
            }
        }
        return null;
    }

    private function getNightConfirmed(Ambassador $ambassador, string $nightField): bool
    {
        return (bool) match($nightField) {
            'bedFriday'   => $ambassador->isBedFriday(),
            'bedSaturday' => $ambassador->isBedSaturday(),
            default       => $ambassador->isBedThursday(),
        };
    }

    private function getNightConfirmedBy(Ambassador $ambassador, string $nightField): ?User
    {
        return match($nightField) {
            'bedFriday'   => $ambassador->getBedFridayUser(),
            'bedSaturday' => $ambassador->getBedSaturdayUser(),
            default       => $ambassador->getBedThursdayUser(),
        };
    }

    #[Route('/evaluations/{letter}', name: 'ambeval_index', methods: ['GET'])]
    public function ambassadorEvalIndex(LetterGroup $letterGroup): Response
    {
        if ($this->getUser()->getLetterGroup() == $letterGroup) {
            return $this->render('evaluations/ambassadors.html.twig', array(
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
    
    #[Route('/evaluations/{id}/do', name: 'ambeval_edit', methods: ['GET', 'POST'])]
    public function ambassadorEvalEditAction(Request $request, Ambassador $ambassador, AmbassadorRepository $ambassadorRepository): Response
    {
        if ($this->getUser()->getLetterGroup() == $ambassador->getLetterGroup()) {
            $editForm = $this->createForm('App\Form\AmbEvaluationType', $ambassador);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $ambassadorRepository->save($ambassador, true);
    
                return $this->redirectToRoute('ambeval_index', array('letter' => $this->getUser()->getLetterGroup()->getLetter()));
            }
    
            return $this->render('evaluations/edit_ambassador.html.twig', array(
                'ambassador' => $ambassador,
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
    
    #[Route('/{id}', name: 'app_ambassador_show', methods: ['GET'])]
    public function show(Ambassador $ambassador): Response
    {
        return $this->render('ambassador/show.html.twig', [
            'ambassador' => $ambassador,
        ]);
    }
    
}
