<?php

namespace App\Service;

use App\Event\HandlerArticleEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class HandlerArticle
{
    private $em;
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();

        // post traitement
        $event = new HandlerArticleEvent($entity);
        $this->dispatcher->dispatch($event, HandlerArticleEvent::NAME);
    }

    public function edit(): void
    {
        $this->em->flush();

        // post traitement
    }
}
