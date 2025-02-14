<?php

namespace App\Repository\Platform\Newsletter;

use App\Entity\Platform\Instance;
use App\Entity\Platform\Newsletter\NewsletterSubscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsletterSubscriber>
 */
class NewsletterSubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSubscriber::class);
    }

    // get all subscribers by instance
    public function findByInstance($instance)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.instance = :instance')
            ->setParameter('instance', $instance)
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function countByInstance(Instance $instance): int
    {
        return $this->createQueryBuilder('ns')
            ->select('count(ns.id)')
            ->where('ns.instance = :instance')
            ->setParameter('instance', $instance)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return NewsletterSubscriber[] Returns an array of NewsletterSubscriber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsletterSubscriber
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
