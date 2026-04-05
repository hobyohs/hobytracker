<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use App\Form\UserRequirementsType;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
    
    public function findAllOrderedByName(): array
        {
            return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u')
                ->from('App:User', 'u')
                ->orderBy('u.lastName', 'ASC')
                ->getQuery()
                ->getResult();    
        }
        
        public function findAllCombinedInfo(): array
        {

            return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u')
                ->from('App:User', 'u')
                ->orderBy('u.lastName', 'ASC')
                ->getQuery()
                ->getResult();
                
        }
        
        public function findAllWithGroups(): array
        {
        
            return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u')
                ->from('App:User', 'u')
                ->orderBy('u.lastName', 'ASC')
                ->where('u.letterGroup IS NOT NULL')
                ->getQuery()
                ->getResult();
                
        }
        
        public function getPsmsLastUpdated()
        {
            $qb = $this->getEntityManager()
                ->createQueryBuilder();
            $sqlTime = $qb    
                ->select('u.psmsUploadedOn')
                ->from('App:User', 'u')
                ->orderBy('u.psmsUploadedOn', 'DESC')
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
                'u.shirtSize',
                'count(u.id) as size_count',
                '(case 
                    when u.shirtSize = :s then 1
                    when u.shirtSize = :m then 2
                    when u.shirtSize = :l then 3
                    when u.shirtSize = :xl then 4
                    when u.shirtSize = :xxl then 5
                    when u.shirtSize = :xxxl then 6
                else 10 end) as sort_order'
                )
                ->groupBy('u.shirtSize')
                ->from('App:User', 'u')
                ->where('u.shirtSize IS NOT NULL')
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
                ->select('COUNT(u.id)')
                ->from('App:User', 'u')
                ->where('u.shirtSize IS NULL')
                ->getQuery()
                ->getSingleScalarResult();
        }
        
        public function findAllWithEvaluations()
        {
            return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('u', 'g')
                ->from('App:User', 'u')
                ->leftJoin('u.letterGroup', 'g')
                ->where('u.eval_status = TRUE')
                ->orderBy('u.lastName', 'ASC')
                ->getQuery()
                ->getResult();   
        }
        
        public function nullEvaluations()
        {
            $qb = $this->getEntityManager()
                ->createQueryBuilder();
            return $qb    
                ->select('COUNT(u.id)')
                ->from('App:User', 'u')
                ->where('u.eval_status = FALSE')
                ->getQuery()
                ->getSingleScalarResult();
        }


}
