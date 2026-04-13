<?php

namespace App\Repository;

use App\Entity\StaffEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffEvaluation>
 */
class StaffEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffEvaluation::class);
    }

    /**
     * All evaluations written about a given staff member (subject).
     */
    public function findAboutStaff(int $staffAssignmentId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.subject = :id')
            ->setParameter('id', $staffAssignmentId)
            ->orderBy('e.seminarYear', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * All evaluations written by a given staff member (evaluator).
     */
    public function findByEvaluator(int $staffAssignmentId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.evaluator = :id')
            ->setParameter('id', $staffAssignmentId)
            ->orderBy('e.seminarYear', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * All submitted evaluations for a given year, for the DOF report.
     */
    public function findSubmittedByYear(int $year): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.subject', 's')
            ->addSelect('s')
            ->where('e.seminarYear = :year')
            ->andWhere('e.status = :status')
            ->setParameter('year', $year)
            ->setParameter('status', 'submitted')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check whether an evaluator has already submitted an eval for a given subject in a given year.
     */
    public function hasSubmitted(int $evaluatorId, int $subjectId, int $year): bool
    {
        $count = $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.evaluator = :evaluator')
            ->andWhere('e.subject = :subject')
            ->andWhere('e.seminarYear = :year')
            ->andWhere('e.status = :status')
            ->setParameter('evaluator', $evaluatorId)
            ->setParameter('subject', $subjectId)
            ->setParameter('year', $year)
            ->setParameter('status', 'submitted')
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
