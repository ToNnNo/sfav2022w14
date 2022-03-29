<?php

namespace App\EventListener\Kernel;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class ExampleListener
{
    // https://symfony.com/blog/new-in-symfony-4-4-simpler-event-listeners
    // s'appuie sur l'argument de la méthode pour définir l'écouteur sur lequel sera posé l'event

    public function __invoke(RequestEvent $event)
    {
        $event->getRequest()->attributes->set('_custom_value', 10);
    }
}
