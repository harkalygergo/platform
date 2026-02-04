<?php

namespace App\Repository\Platform;

use App\Entity\Platform\Event;
use App\Entity\Platform\Website\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
final class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[]
     */
    public function findUpcoming(Website $website, int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.startAt >= :now')
            ->andWhere('e.website = :website')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('website', $website)
            ->orderBy('e.startAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

