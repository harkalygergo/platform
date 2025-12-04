<?php

namespace App\Repository\Platform\Website;

use App\Entity\Platform\Website\WebsiteMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class WebsiteMediaRepository extends ServiceEntityRepository
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(ManagerRegistry $registry, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($registry, WebsiteMedia::class);
        $this->authorizationChecker = $authorizationChecker;
    }
}
