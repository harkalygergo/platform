<?php

namespace App\Repository\Platform\Webshop;

use App\Entity\Platform\Webshop\Webshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WebshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Webshop::class);
    }
}
