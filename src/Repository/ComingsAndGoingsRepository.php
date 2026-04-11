<?php

namespace App\Repository;

use App\Entity\ComingsAndGoings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComingsAndGoings>
 *
 * @method ComingsAndGoings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComingsAndGoings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComingsAndGoings[]    findAll()
 * @method ComingsAndGoings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComingsAndGoingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComingsAndGoings::class);
    }

    public function save(ComingsAndGoings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ComingsAndGoings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.active = :active')
            ->setParameter('active', true)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ComingsAndGoings[] Returns an array of ComingsAndGoings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComingsAndGoings
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
