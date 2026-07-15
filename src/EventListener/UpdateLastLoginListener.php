<?php
namespace App\EventListener;

use App\Entity\Platform\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'security.interactive_login')]
final class UpdateLastLoginListener
{
    public function __construct(private ManagerRegistry $doctrine) {}

    public function __invoke(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (! $user instanceof User) {
            return;
        }

        $user->setLastLogin(new \DateTimeImmutable());
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
    }
}
