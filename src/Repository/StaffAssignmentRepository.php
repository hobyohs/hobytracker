<?php

namespace App\Repository;

use App\Entity\StaffAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffAssignment>
 */
class StaffAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffAssignment::class);
    }

    public function save(StaffAssignment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StaffAssignment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('sa')
            ->andWhere('sa.seminarYear = :year')
            ->setParameter('year', $year)
            ->leftJoin('sa.user', 'u')
            ->addSelect('u')
            ->orderBy('u.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByYear(int $year): array
    {
        return $this->createQueryBuilder('sa')
            ->andWhere('sa.seminarYear = :year')
            ->andWhere('sa.status = :status')
            ->setParameter('year', $year)
            ->setParameter('status', 'active')
            ->leftJoin('sa.user', 'u')
            ->addSelect('u')
            ->orderBy('u.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function shirtSizeReport(int $year): array
    {
        return $this->createQueryBuilder('sa')
            ->select(
                'sa.shirtSize',
                'COUNT(sa.id) as size_count',
                '(CASE
                    WHEN sa.shirtSize = :s THEN 1
                    WHEN sa.shirtSize = :m THEN 2
                    WHEN sa.shirtSize = :l THEN 3
                    WHEN sa.shirtSize = :xl THEN 4
                    WHEN sa.shirtSize = :xxl THEN 5
                    WHEN sa.shirtSize = :xxxl THEN 6
                ELSE 10 END) as sort_order'
            )
            ->andWhere('sa.seminarYear = :year')
            ->andWhere('sa.shirtSize IS NOT NULL')
            ->setParameter('year', $year)
            ->setParameter('s', 'S')
            ->setParameter('m', 'M')
            ->setParameter('l', 'L')
            ->setParameter('xl', 'XL')
            ->setParameter('xxl', 'XXL')
            ->setParameter('xxxl', 'XXXL')
            ->groupBy('sa.shirtSize')
            ->orderBy('sort_order')
            ->getQuery()
            ->getResult();
    }

    public function nullShirtSizes(int $year): int
    {
        return (int) $this->createQueryBuilder('sa')
            ->select('COUNT(sa.id)')
            ->andWhere('sa.seminarYear = :year')
            ->andWhere('sa.shirtSize IS NULL')
            ->setParameter('year', $year)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPsmsLastUpdated(int $year): string
    {
        $result = $this->createQueryBuilder('sa')
            ->select('sa.psmsUploadedOn')
            ->andWhere('sa.seminarYear = :year')
            ->setParameter('year', $year)
            ->orderBy('sa.psmsUploadedOn', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

        if (!empty($result)) {
            $date = new \DateTime($result);
            return $date->format('M j, Y \a\t g:i A');
        }
        return 'PSMs not yet updated.';
    }

    /**
     * Search active staff assignments by name for the given year.
     * Used by the bed check assignment Tom Select picker.
     */
    public function searchActiveByName(string $query, int $year, int $limit = 10): array
    {
        return $this->createQueryBuilder('sa')
            ->join('sa.user', 'u')
            ->where('sa.seminarYear = :year')
            ->andWhere('sa.status = :status')
            ->andWhere('u.firstName LIKE :q OR u.lastName LIKE :q OR u.prefName LIKE :q')
            ->setParameter('year', $year)
            ->setParameter('status', 'active')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('u.lastName', 'ASC')
            ->addOrderBy('u.firstName', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
