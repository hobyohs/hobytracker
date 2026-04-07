<?php

namespace App\Repository;

use App\Entity\Ambassador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ambassador>
 *
 * @method Ambassador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ambassador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ambassador[]    findAll()
 * @method Ambassador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmbassadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ambassador::class);
    }

    public function save(Ambassador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ambassador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a', 'g')
            ->from('App\Entity\Ambassador', 'a')
            ->leftJoin('a.letterGroup', 'g')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult(); 
    }
    
     public function findAllNotCheckedOutOrderedByName()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a', 'g')
            ->from('App\Entity\Ambassador', 'a')
            ->leftJoin('a.letterGroup', 'g')
            ->where('a.checkedOut = 0')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult();   
    }
    
    public function countAll()
    {
        return count($this->findAllOrderedByName());  
    }
    
    public function findAllCombinedInfo()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a')
            ->from('App\Entity\Ambassador', 'a')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function findAllWithGroups()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('a')
            ->from('App\Entity\Ambassador', 'a')
            ->where($qb->expr()->isNotNull('a.letterGroup'))
            ->getQuery()
            ->getResult();
    }
    
    public function findThoseTakingBus()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('a')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.takingBus = TRUE')
            ->getQuery()
            ->getResult();
    }
    
    public function findCheckinList()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('a')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.checkedIn = 0')
            ->getQuery()
            ->getResult();
    }
    
    public function countCheckedIn()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.checkedIn = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
     public function findCheckoutList()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('a')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.checkedOut = 0')
            ->andWhere('a.checkedIn = 1')
            ->getQuery()
            ->getResult();
    }
    
    public function countCheckedOut()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.checkedOut = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countJuniorCalled()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.juniorCallMade = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countJuniorCallSuccess()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.juniorCallMade = 1')
            ->andWhere('a.juniorCallDisposition = :dispo')
            ->setParameter('dispo', 'spoke_with_ambassador')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countPsmReturned()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.checkin_paperwork = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function getPsmsLastUpdated()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        $sqlTime = $qb    
            ->select('a.psms_uploaded_on')
            ->from('App\Entity\Ambassador', 'a')
            ->orderBy('a.psms_uploaded_on', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
        
        if (!empty($sqlTime)) {
            $date = new \DateTime($sqlTime);
            return $date->format('M j, Y \a\t g:i A');
        } else {
            return "PSMs not yet updated.";
        }
    }
    
    public function shirtSizeReport()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select(
                'a.shirtSize',
                'count(a.id) as size_count',
                '(case 
                    when a.shirtSize = :s then 1
                    when a.shirtSize = :m then 2
                    when a.shirtSize = :l then 3
                    when a.shirtSize = :xl then 4
                    when a.shirtSize = :xxl then 5
                    when a.shirtSize = :xxxl then 6
                else 10 end) as sort_order'
                )
            ->groupBy('a.shirtSize')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.shirtSize IS NOT NULL')
            ->setParameter('s', 'S')
            ->setParameter('m', 'M')
            ->setParameter('l', 'L')
            ->setParameter('xl', 'XL')
            ->setParameter('xxl', 'XXL')
            ->setParameter('xxxl', 'XXXL')
            ->orderBy('sort_order')
            ->getQuery()
            ->getResult();
    }
    
    public function nullShirtSizes()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.shirtSize IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function findAllWithEvaluations()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a', 'g')
            ->from('App\Entity\Ambassador', 'a')
            ->leftJoin('a.letterGroup', 'g')
            ->where('a.eval_recommendation IS NOT NULL')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult();   
    }
    
    public function nullEvaluations()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb    
            ->select('COUNT(a.id)')
            ->from('App\Entity\Ambassador', 'a')
            ->where('a.eval_recommendation IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }


}
