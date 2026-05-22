<?php

// src/EventSubscriber/TimestampableSubscriber.php
namespace App\EventSubscriber\Platform;

use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class TimestampableSubscriber
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        $entity->setCreatedAt(new \DateTimeImmutable());

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $entity->setCreatedBy($user);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $entity->setUpdatedBy($user);
        }
    }
}
