<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE)]
final class AddCookieOnResponseListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if (! $event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();
        if (! $session || ! $session->has('just_logged_in')) {
            return;
        }

        $instanceId = $session->get('login_default_instance_id');
        if ($instanceId) {
            $cookie = Cookie::create(
                'currentInstance',
                (string) $instanceId,
                strtotime('+30 days'),
                '/',
                null,
                false, // secure — change to true if using HTTPS only
                true   // httpOnly
            );

            $response = $event->getResponse();
            $response->headers->setCookie($cookie);
        }

        // cleanup flags
        $session->remove('just_logged_in');
        $session->remove('login_default_instance_id');
    }
}
