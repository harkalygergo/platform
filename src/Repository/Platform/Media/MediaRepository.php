<?php

namespace App\Repository\Platform\Media;

use App\Entity\Platform\Media\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MediaRepository extends ServiceEntityRepository
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(ManagerRegistry $registry, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($registry, Media::class);
        $this->authorizationChecker = $authorizationChecker;
    }

    public function findAllVisible(): array
    {
        if (!$this->authorizationChecker->isGranted('ROLE_PLATFORM_MEDIA_VIEW')) {
            throw new AccessDeniedException('You do not have permission to view media.');
        }

        return $this->findBy(['status' => true]);
    }

    public function createQueryBuilderForMedia(): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->where('m.status = :status')
            ->setParameter('status', true);
    }
}
