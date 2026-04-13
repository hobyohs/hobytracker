<?php

namespace App\Repository;

use App\Entity\Seminar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seminar>
 */
class SeminarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seminar::class);
    }

    public function findByYear(int $year): ?Seminar
    {
        return $this->findOneBy(['year' => $year]);
    }
}
