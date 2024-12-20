<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BannedListener implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Ne traiter que les requêtes principales (pas les sous-requêtes)
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        // Exclure la page /ban de la redirection
        if ($request->getPathInfo() === '/ban') {
            return;
        }
        
        // Vérifiez si l'utilisateur est connecté et a le rôle ROLE_BANNED
        $user = $this->security->getUser();

        if ($user && $this->security->isGranted('ROLE_BANNED')) {
            // Rediriger vers la page /ban
            $response = new RedirectResponse('/ban');
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
