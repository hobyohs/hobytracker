<?php

namespace App\Repository;

use App\Entity\DormRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DormRoom>
 *
 * @method DormRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DormRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DormRoom[]    findAll()
 * @method DormRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DormRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DormRoom::class);
    }

    public function save(DormRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DormRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns all DormRooms ordered by dorm, floor, sort_order.
     * Used to build the bed checks floor groupings.
     */
    public function findAllOrderedForBedChecks(): array
    {
        return $this->createQueryBuilder('dr')
            ->select('dr')
            ->orderBy('dr.dorm', 'ASC')
            ->addOrderBy('dr.floor', 'ASC')
            ->addOrderBy('dr.sort_order', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

}
