<?php

namespace App\Repository\Platform\Ecom;

use App\Entity\Platform\Ecom\Product;
use App\Entity\Platform\Website\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByWebsiteAndStatus(Website $website, bool $status): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.websites', 'w')
            ->where('w = :website')
            ->andWhere('p.status = :status')
            ->setParameter('website', $website)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }


    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
