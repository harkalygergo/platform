<?php

namespace App\Repository\Platform\Newsletter;

use App\Entity\Platform\Newsletter\NewsletterSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsletterSettings>
 */
class NewsletterSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSettings::class);
    }
}
