<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UselessSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $methods = array_merge(
            $event->getRequest()->attributes->get('_custom_methods_called', []),
            ['App\EventSubscriber\UselessSubscriber::onKernelRequest']
        );

        $event->getRequest()->attributes->set('_custom_methods_called', $methods); // FQCN Fully Qualified Class Name
    }

    public function secondKernelRequest(RequestEvent $event)
    {
        $methods = array_merge(
            $event->getRequest()->attributes->get('_custom_methods_called', []),
            ['App\EventSubscriber\UselessSubscriber::secondKernelRequest']
        );

        $event->getRequest()->attributes->set('_custom_methods_called', $methods);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => [
                ['onKernelRequest', -1],
                ['secondKernelRequest', -2]
            ],
        ];
    }
}
