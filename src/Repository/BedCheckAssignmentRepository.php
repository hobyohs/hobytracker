<?php

namespace App\Repository;

use App\Entity\BedCheckAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BedCheckAssignment>
 */
class BedCheckAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BedCheckAssignment::class);
    }

    /**
     * Returns all assignments for a given seminar year, indexed for fast lookup.
     * Result is an array keyed by "dorm|||floor|||night" → array of BedCheckAssignment[].
     */
    public function findAllByYearIndexed(int $year): array
    {
        $assignments = $this->createQueryBuilder('b')
            ->join('b.staffAssignment', 'sa')
            ->addSelect('sa')
            ->where('b.seminarYear = :year')
            ->setParameter('year', $year)
            ->orderBy('sa.user', 'ASC')
            ->getQuery()
            ->getResult();

        $indexed = [];
        foreach ($assignments as $a) {
            $key = $a->getDorm() . '|||' . $a->getFloor() . '|||' . $a->getNight();
            $indexed[$key][] = $a;
        }
        return $indexed;
    }

    /**
     * Returns all assignments for a specific staff assignment (user + year).
     */
    public function findByStaffAssignment(int $staffAssignmentId): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.staffAssignment = :saId')
            ->setParameter('saId', $staffAssignmentId)
            ->orderBy('b.night', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns assignments for a specific user in a given year, for the duty assignments page.
     */
    public function findByUserAndYear(int $userId, int $year): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.staffAssignment', 'sa')
            ->where('sa.user = :userId')
            ->andWhere('b.seminarYear = :year')
            ->setParameter('userId', $userId)
            ->setParameter('year', $year)
            ->orderBy('b.night', 'ASC')
            ->addOrderBy('b.dorm', 'ASC')
            ->addOrderBy('b.floor', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
