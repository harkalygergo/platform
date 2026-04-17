<?php

namespace App\Repository\Platform\Order;

use App\Entity\Platform\Order\OrderEmailTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderEmailTemplate>
 */
class OrderEmailTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEmailTemplate::class);
    }
}
