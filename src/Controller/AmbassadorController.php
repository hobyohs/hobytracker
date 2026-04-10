<?php

namespace App\Controller;

use App\Entity\Ambassador;
use App\Entity\ComingsAndGoings;
use App\Form\AmbassadorType;
use App\Repository\AmbassadorRepository;
use App\Entity\LetterGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        
        if (count($ambassador->getComingsAndGoings()) > 0) {
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
                $entityManager->persist($cg);
            }
            if ($session->get($medsOverrideKey, false)) {
                $ambassador->setCheckinMeds(true);
            }

            $entityManager->persist($ambassador);
            $entityManager->flush();

            // Clear session overrides for this ambassador now that they're persisted
            $session->remove($cgOverrideKey);
            $session->remove($medsOverrideKey);

            return $this->redirectToRoute('app_ambassador_checkin');
        }
        
        if (count($ambassador->getComingsAndGoings()) > 0) {
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
        
        if ($ambassador->getCurrentRx() != '') $meds_status = TRUE;
        else $meds_status = FALSE;
        
        // if ($ambassador->isStoreItems()) $store_status = TRUE;
        // else $store_status = FALSE;

        return $this->render('ambassador/checkin/form.html.twig', array(
            'ambassador' => $ambassador,
            'edit_form' => $editForm->createView(),
            'cg_status' => $cg_status,
            'psm_status' => $psm_status,
            'deposit_status' => $deposit_status,
            'meds_status' => $meds_status,
            'cg_override' => $session->get($cgOverrideKey, false),
            'meds_override' => $session->get($medsOverrideKey, false),
//             'doc_status' => $doc_status,
          //  'store_status' => $store_status,
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
    public function bedChecksAction(AmbassadorRepository $ambassadorRepository): Response
    {
        return $this->render('ambassador/bedchecks.html.twig', array(
            'ambassadors' => $ambassadorRepository->findAllNotCheckedOutOrderedByName()
        ));
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
