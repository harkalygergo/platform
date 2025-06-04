<?php

namespace App\Repository\Platform\Website;

use App\Entity\Platform\Website\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use App\Entity\Platform\Website\Website;

class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findByWebsiteId(int $websiteId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.website = :websiteId')
            ->setParameter('websiteId', $websiteId)
            ->getQuery()
            ->getResult();
    }

    public function findOneByWebsiteAndSlug(Website $website, string $slug): ?Menu
    {
        try {
            return $this->createQueryBuilder('m')
                ->where('m.website = :website')
                ->andWhere('m.slug = :slug')
                ->setParameter('website', $website)
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return null;
        }
    }
}
