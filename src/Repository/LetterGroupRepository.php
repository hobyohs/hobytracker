<?php

namespace App\Repository;

use App\Entity\LetterGroup;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LetterGroup>
 *
 * @method LetterGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method LetterGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method LetterGroup[]    findAll()
 * @method LetterGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LetterGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LetterGroup::class);
    }

    public function save(LetterGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LetterGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function pullDemoSummary() {
        
        $em = $this->getEntityManager();
        
        $groups = $em
            ->createQueryBuilder()
            ->select('g')
            ->from('App\Entity\LetterGroup', 'g')
            ->getQuery()
            ->getResult();
            
        $returnVar = [];
        
        foreach ($groups as $group) {
            
            $totalAmb = count($group->getAmbassadors());
            
            $qb1 = $em->createQueryBuilder();
            $boyAmb = $qb1
                ->select($qb1->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.gender = :gender1 OR a.gender = :gender2')
                ->setParameter('gender1', 'Male')
                ->setParameter('gender2', 'A man or boy')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
             
            // $qb2 = $em->createQueryBuilder();   
            // $boyAmb = $maleAmb+$qb2
            //     ->select($qb2->expr()->count('a.id'))
            //     ->from('App\Entity\LetterGroup', 'g')
            //     ->leftJoin('g.ambassadors', 'a')
            //     ->where('g = :group')
            //     ->andWhere('a.gender = :sex')
            //     ->setParameter('sex', 'Male')
            //     ->setParameter('group', $group)
            //     ->getQuery()
            //     ->getSingleScalarResult();
                           
                
            $qb3 = $em->createQueryBuilder();
            $girlAmb = $qb3
                ->select($qb3->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.gender = :gender1 OR a.gender = :gender2')
                ->setParameter('gender1', 'Female')
                ->setParameter('gender2', 'A woman or girl')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
             
            $qb4 = $em->createQueryBuilder();   
            $otherGenderAmb = $qb4
                ->select($qb4->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.gender <> :male')
                ->andWhere('a.gender <> :female')
                ->andWhere('a.gender <> :boy')
                ->andWhere('a.gender <> :girl')
                ->setParameter('male', 'Male')
                ->setParameter('female', 'Female')
                ->setParameter('boy', 'A man or boy')
                ->setParameter('girl', 'A woman or girl')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
                
            $qb6 = $em->createQueryBuilder();
            $whiteAmb = $qb6
                ->select($qb6->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.ethnicity = :old OR a.ethnicity = :new')
                ->setParameter('old', 'White or Caucasian')
                ->setParameter('new', 'White')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
                
            $qb7 = $em->createQueryBuilder();
            $nonwhiteAmb = $qb7
                ->select($qb7->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.ethnicity <> :ethnicity')
                ->setParameter('ethnicity', 'White or Caucasian')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
                
            $qb8 = $em->createQueryBuilder();
            $unknownAmb = $qb8
                ->select($qb8->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.ethnicity is NULL')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
                
            $qb9 = $em->createQueryBuilder();
            $fcAmb = $qb9
                ->select($qb9->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.county = :county')
                ->setParameter('county', 'Franklin')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
                
            $qb10 = $em->createQueryBuilder();
            $nonfcAmb = $qb10
                ->select($qb10->expr()->count('a.id'))
                ->from('App\Entity\LetterGroup', 'g')
                ->leftJoin('g.ambassadors', 'a')
                ->where('g = :group')
                ->andWhere('a.county <> :county')
                ->setParameter('county', 'Franklin')
                ->setParameter('group', $group)
                ->getQuery()
                ->getSingleScalarResult();
            
            $returnVar[] = [
                'letter' => $group->getLetter(),
                'id' => $group->getId(),
                'ambassadors' => $totalAmb,
                'boys' => $boyAmb,
                'girls' => $girlAmb,
                'other_gender' => $otherGenderAmb,
                'whites' => $whiteAmb,
                'nonwhites' => $nonwhiteAmb,
                'unknown' => $unknownAmb,
                'fc' => $fcAmb,
                'nonfc' => $nonfcAmb
            ];
        }
        
        return $returnVar;
    }

}
