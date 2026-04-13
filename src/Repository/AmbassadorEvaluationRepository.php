<?php

namespace App\Repository;

use App\Entity\AmbassadorEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AmbassadorEvaluation>
 */
class AmbassadorEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AmbassadorEvaluation::class);
    }

    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ambassador', 'a')
            ->addSelect('a')
            ->where('e.seminarYear = :year')
            ->setParameter('year', $year)
            ->orderBy('a.lastName', 'ASC')
            ->addOrderBy('a.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findSubmittedByYear(int $year): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ambassador', 'a')
            ->addSelect('a')
            ->leftJoin('a.letterGroup', 'g')
            ->addSelect('g')
            ->leftJoin('e.submittedBy', 'sb')
            ->addSelect('sb')
            ->where('e.seminarYear = :year')
            ->andWhere('e.status = :status')
            ->setParameter('year', $year)
            ->setParameter('status', 'submitted')
            ->orderBy('g.letter', 'ASC')
            ->addOrderBy('a.lastName', 'ASC')
            ->addOrderBy('a.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findForAmbassador(int $ambassadorId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.ambassador = :id')
            ->setParameter('id', $ambassadorId)
            ->orderBy('e.seminarYear', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
