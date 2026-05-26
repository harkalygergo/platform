<?php

namespace App\Repository\Platform\CMS;

use App\Entity\Platform\CMS\VisitorLog;
use App\Entity\Platform\Instance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VisitorLog>
 */
class VisitorLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitorLog::class);
    }

    public function getCurrentMonthVisitsSum(Instance $instance): int
    {
        $startOfMonth = new \DateTimeImmutable('first day of this month 00:00:00');
        $startOfNextMonth = $startOfMonth->modify('+1 month');

        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.instance = :instance')
            ->andWhere('v.visitedAt >= :startOfMonth')
            ->andWhere('v.visitedAt < :startOfNextMonth')
            ->setParameter('instance', $instance)
            ->setParameter('startOfMonth', $startOfMonth)
            ->setParameter('startOfNextMonth', $startOfNextMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
