<?php

namespace App\Repository;

use App\Entity\Applicant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Applicant>
 */
class ApplicantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Applicant::class);
    }
    
    public function save(Applicant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
    
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByName(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a')
            ->from('App\Entity\Applicant', 'a')
            ->orderBy('a.lastName', 'ASC')
            ->addOrderBy('a.firstName', 'ASC')
            ->getQuery()
            ->getResult();    
    }
    
    public function pullSummary() {
        
        $em = $this->getEntityManager();
        
        $qb1 = $em->createQueryBuilder();
        $totalApplicants = $qb1
            ->select($qb1->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->getQuery()
            ->getSingleScalarResult();
            
        $qb2 = $em->createQueryBuilder();
        $yeses = $qb2
            ->select($qb2->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision1 OR a.decision = :decision2 OR a.decision = :decision3')
            ->setParameter('decision1', 'Facilitator')
            ->setParameter('decision2', 'J-Crew')
            ->setParameter('decision3', 'Team HQ')
            ->getQuery()
            ->getSingleScalarResult();
                
        $qb3 = $em->createQueryBuilder();
        $maybes = $qb3
            ->select($qb3->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->setParameter('decision', 'Tentative')
            ->getQuery()
            ->getSingleScalarResult();
                    
        $qb4 = $em->createQueryBuilder();
        $nos = $qb4
            ->select($qb4->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->setParameter('decision', 'No Hire')
            ->getQuery()
            ->getSingleScalarResult();
                        
        $qb5 = $em->createQueryBuilder();
        $juniorFacilitators = $qb5
            ->select($qb5->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->andWhere('a.age < 21')
            ->setParameter('decision', 'Facilitator')
            ->getQuery()
            ->getSingleScalarResult();
                            
        $qb6 = $em->createQueryBuilder();
        $jcrew = $qb6
            ->select($qb6->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->andWhere('a.age < 21')
            ->setParameter('decision', 'J-Crew')
            ->getQuery()
            ->getSingleScalarResult();
                                
        $qb7 = $em->createQueryBuilder();
        $seniorFacilitators = $qb7
            ->select($qb7->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->andWhere('a.age >= 21')
            ->setParameter('decision', 'Facilitator')
            ->getQuery()
            ->getSingleScalarResult();
                                    
        $qb8 = $em->createQueryBuilder();
        $teamHq = $qb8
            ->select($qb8->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->andWhere('a.age >= 21')
            ->setParameter('decision', 'Team HQ')
            ->getQuery()
            ->getSingleScalarResult();
                                    
        $qb9 = $em->createQueryBuilder();
        $juniorsToGo = $qb9
            ->select($qb9->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision IS NULL')
            ->andWhere('a.age < 21')
            ->getQuery()
            ->getSingleScalarResult();
                                        
        $qb10 = $em->createQueryBuilder();
        $seniorsToGo = $qb10
            ->select($qb10->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision IS NULL')
            ->andWhere('a.age >= 21')
            ->getQuery()
            ->getSingleScalarResult();
            
        $qb11 = $em->createQueryBuilder();
        $seniorNoHires = $qb11
            ->select($qb11->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->setParameter('decision', 'No Hire')
            ->andWhere('a.age >= 21')
            ->getQuery()
            ->getSingleScalarResult();
                
        $qb12 = $em->createQueryBuilder();
        $juniorNoHires = $qb12
            ->select($qb12->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision = :decision')
            ->setParameter('decision', 'No Hire')
            ->andWhere('a.age < 21')
            ->getQuery()
            ->getSingleScalarResult();
                    
        $qb13 = $em->createQueryBuilder();
        $totalJuniors = $qb13
            ->select($qb13->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->andWhere('a.age < 21')
            ->getQuery()
            ->getSingleScalarResult();
                        
        $qb14 = $em->createQueryBuilder();
        $totalSeniors = $qb14
            ->select($qb14->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->andWhere('a.age >= 21')
            ->getQuery()
            ->getSingleScalarResult();
            
        $qb15 = $em->createQueryBuilder();
        $totalToGo = $qb15
            ->select($qb15->expr()->count('a.id'))
            ->from('App\Entity\Applicant', 'a')
            ->where('a.decision IS NULL')
            ->getQuery()
            ->getSingleScalarResult();

        $returnVar = [
            'total' => $totalApplicants,
            'yeses' => $yeses,
            'maybes' => $maybes,
            'nos' => $nos,
            'juniorFacilitators' => $juniorFacilitators,
            'jcrew' => $jcrew,
            'seniorFacilitators' => $seniorFacilitators,
            'teamHq' => $teamHq,
            'juniorsToGo' => $juniorsToGo,
            'seniorsToGo' => $seniorsToGo,
            'seniorNoHires' => $seniorNoHires,
            'juniorNoHires' => $juniorNoHires,
            'totalJuniors' => $totalJuniors,
            'totalSeniors' => $totalSeniors,
            'totalToGo' => $totalToGo,
            'seniorsAccepted' => $seniorFacilitators+$teamHq,
            'juniorsAccepted' => $juniorFacilitators + $jcrew
        ];
                
        return $returnVar;
    }
    
}
