<?php

namespace App\EventSubscriber;

use App\Event\HandlerArticleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotifierSubscriber implements EventSubscriberInterface
{
    public function onHandlerArticleCreated(HandlerArticleEvent $event)
    {
        dump("Un nouvel article a été ajouté : ".$event->getPost()->getTitle());

        $event->getPost()->setTitle( $event->getPost()->getTitle()." (updated)" );

        // $event->stopPropagation();
    }

    public function createDocument(HandlerArticleEvent $event)
    {
        dump('create document: '.$event->getPost()->getTitle());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'handler_article.created' => [
                ['onHandlerArticleCreated', 256],
                ['createDocument', 0]
            ]
        ];
    }
}
