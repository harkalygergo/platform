<?php

namespace App\Repository\Platform;

use App\Entity\Platform\BillingProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingProfile>
 */
class BillingProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingProfile::class);
    }

    public function findByUserInstances($instances)
    {
        return $this->createQueryBuilder('bp')
            ->innerJoin('bp.instances', 'i')
            ->where('i IN (:instances)')
            ->setParameter('instances', $instances)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return BillingProfile[] Returns an array of BillingProfile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BillingProfile
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
