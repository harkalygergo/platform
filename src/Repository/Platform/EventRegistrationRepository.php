<?php

namespace App\Repository\Platform;

use App\Entity\Platform\Event;
use App\Entity\Platform\EventRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventRegistration>
 */
final class EventRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRegistration::class);
    }

    /**
     * @return EventRegistration[]
     */
    public function findByEvent(Event $event): array
    {
        return $this->createQueryBuilder('er')
            ->where('er.event = :event')
            ->setParameter('event', $event)
            ->orderBy('er.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
