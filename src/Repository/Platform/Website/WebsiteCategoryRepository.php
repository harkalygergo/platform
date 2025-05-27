<?php

namespace App\Repository\Platform\Website;

use App\Entity\Platform\Website\WebsiteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebsiteCategory>
 *
 * @method WebsiteCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteCategory[]    findAll()
 * @method WebsiteCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteCategory::class);
    }

    public function findByWebsiteId(int $websiteId): array
    {
        return $this->createQueryBuilder('wp')
            ->andWhere('wp.website = :websiteId')
            ->setParameter('websiteId', $websiteId)
            ->getQuery()
            ->getResult();
    }
}
