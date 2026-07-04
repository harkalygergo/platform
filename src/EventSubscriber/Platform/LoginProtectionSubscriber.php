<?php

namespace App\EventSubscriber\Platform;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LoginProtectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 20], // security listener ~8, tehát 20 = előtte fut
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Csak a login POST kérésekre
        if (!$event->isMainRequest()) {
            return;
        }

        if ($request->getPathInfo() !== '/') {
            return;
        }

        if ($request->getMethod() !== 'POST') {
            return;
        }

        $username = $request->request->get('_username');

        if ($username === null || trim($username) === '') {
            $event->setResponse(new Response('Bad Request', 400));
            return;
        }

        // Content-Type ellenőrzés – egy böngésző form mindig ezt küldi
        $contentType = $request->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'application/x-www-form-urlencoded')
            && !str_contains($contentType, 'multipart/form-data')
        ) {
            $event->setResponse(new Response('Bad Request', 400));
            return;
        }

        // CSRF token meglétének ellenőrzése (értéket nem tudjuk itt validálni)
        $csrfToken = $request->request->get('_csrf_token');
        if ($csrfToken === null) {
            $event->setResponse(new Response('Bad Request', 400));
            return;
        }
    }
}
