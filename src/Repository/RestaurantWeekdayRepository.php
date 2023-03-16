<?php

namespace App\Repository;

use App\Entity\RestaurantWeekday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RestaurantWeekday>
 *
 * @method RestaurantWeekday|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantWeekday|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantWeekday[]    findAll()
 * @method RestaurantWeekday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantWeekdayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantWeekday::class);
    }

    public function save(RestaurantWeekday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RestaurantWeekday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RestaurantWeekday[] Returns an array of RestaurantWeekday objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RestaurantWeekday
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
