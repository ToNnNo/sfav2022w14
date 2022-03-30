<?php

namespace App\EventSubscriber;

use App\Event\HandlerArticleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;

class NotifierSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $router;

    public function __construct(MailerInterface $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function onHandlerArticleCreated(HandlerArticleEvent $event)
    {
        $post = $event->getPost();

        $message = <<<EOF
Bonjour,

Un nouvel article vient d'être ajouté par John Doe.
Titre: {$post->getTitle()}.

Consulter l'article en ligne: {$this->router->generate('article_detail', ['id' => $post->getId()], RouterInterface::ABSOLUTE_URL)}
--
Notification Automatique
Yona App - Dawan - Formation Symfony 
EOF;

        $email = (new Email())
            ->from(new Address('notifier@dawan.fr', 'Centre de notification'))
            ->replyTo(new Address('noreply@dawan.fr', 'NoReply'))
            ->to('smenut@dawan.fr')
            ->subject('Nouvel Article inséré')
            ->text($message)
            ->html(nl2br($message))
        ;

        $this->mailer->send($email);

        // dump("Un nouvel article a été ajouté : ".$event->getPost()->getTitle());

        // $event->getPost()->setTitle( $event->getPost()->getTitle()." (updated)" );

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
