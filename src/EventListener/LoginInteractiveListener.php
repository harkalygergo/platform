<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\Platform\User;

#[AsEventListener(event: InteractiveLoginEvent::class)]
final class LoginInteractiveListener
{
    public function __invoke(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        if ($user instanceof User) {
            $defaultInstance = $user->getDefaultInstance();
            if ($defaultInstance) {
                $session = $request->getSession();
                // mark that we just logged in and store instance id for the response listener
                $session->set('just_logged_in', true);
                $session->set('login_default_instance_id', $defaultInstance->getId());
            }
        }
    }
}
