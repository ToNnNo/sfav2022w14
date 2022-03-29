<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class AddAttributeEventListener
{

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if( $requestEvent->isMainRequest() ) {
            $attributes = $requestEvent->getRequest()->attributes;
            if('Miss' === $attributes->get('_custom_gender')) {
                $attributes->set('_custom_username', 'Jane Doe');
            } else {
                $attributes->set('_custom_username', 'John Doe');
            }
        }
    }

    public function addAttributeGender(RequestEvent $requestEvent): void
    {
        if( $requestEvent->isMainRequest() ) {
            $requestEvent->getRequest()->attributes->set('_custom_gender', 'Miss'); // Mister
        }
    }

}
