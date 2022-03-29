<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class RedirectSubscriber implements EventSubscriberInterface
{
    private $router;

    private const redirectTab = [
        ['match_url' => '/no-way', 'route_redirect' => 'home_index', 'params' => []]
    ];

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $key = array_search($request->getPathInfo(), array_column(self::redirectTab, 'match_url'));

        if($key !== false) {
            $info = self::redirectTab[$key];

            $url = $this->router->generate($info['route_redirect'], $info['params']);

            $event->setResponse(new RedirectResponse($url, 301));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => [
                ['onKernelRequest', 256]
            ],
        ];
    }
}
