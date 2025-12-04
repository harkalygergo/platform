<?php

namespace App\Repository\Platform\Website;

use App\Entity\Platform\Website\WebsitePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebsitePost>
 *
 * @method WebsitePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsitePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsitePost[]    findAll()
 * @method WebsitePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsitePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsitePost::class);
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
