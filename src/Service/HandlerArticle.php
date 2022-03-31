<?php

namespace App\Service;

use App\Entity\Post;
use App\Event\HandlerArticleEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class HandlerArticle
{
    private $em;
    private $dispatcher;
    private $fileManager;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher, FileManager $fileManager)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->fileManager = $fileManager;
    }

    public function add($entity): void
    {
        $this->upload($entity);
        $this->em->persist($entity);
        $this->em->flush();

        // post traitement
        $event = new HandlerArticleEvent($entity);
        $this->dispatcher->dispatch($event, HandlerArticleEvent::NAME);
    }

    public function edit($entity): void
    {
        $this->upload($entity);
        $this->em->flush();

        // post traitement
    }

    public function upload($entity): void
    {
        if($entity->getFile() instanceof UploadedFile) {
            if( null !== $entity->getImage() ) {
                $this->fileManager->setExistingImage($entity->getImage());
            }

            $name = $this->fileManager->setDirectory(Post::IMAGE_DIRECTORY)->uploadFile($entity->getFile());
            $entity->setImage($name);
        }
    }
}
