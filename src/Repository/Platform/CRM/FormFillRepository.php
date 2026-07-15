<?php

namespace App\Repository\Platform\CRM;

use App\Entity\Platform\CMS\FormFill;
use App\Entity\Platform\Instance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormFill>
 */
class FormFillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFill::class);
    }

    public function findByCreatedAtDesc(Instance $currentInstance): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.instance = :instance')
            ->setParameter('instance', $currentInstance)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
