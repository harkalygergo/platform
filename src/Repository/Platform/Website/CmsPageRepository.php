<?php

namespace App\Repository\Platform\Website;

use App\Entity\Platform\Website\CmsPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CmsPage>
 *
 * @method CmsPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsPage[]    findAll()
 * @method CmsPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsPage::class);
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
