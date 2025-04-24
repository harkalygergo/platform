<?php

namespace App\Repository\Platform\API;

use App\Entity\Platform\API\API;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<API>
 *
 * @method API|null find($id, $lockMode = null, $lockVersion = null)
 * @method API|null findOneBy(array $criteria, array $orderBy = null)
 * @method API[]    findAll()
 * @method API[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class APIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, API::class);
    }

    public function save(API $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(API $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

}
