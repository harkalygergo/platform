<?php

namespace App\Repository\Platform\Instance;

use App\Entity\Platform\Instance\InstanceFeed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InstanceFeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstanceFeed::class);
    }
}
