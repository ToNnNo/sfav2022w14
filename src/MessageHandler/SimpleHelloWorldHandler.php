<?php

namespace App\MessageHandler;

use App\Message\SimpleHelloWorld;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SimpleHelloWorldHandler implements MessageHandlerInterface
{
    public function __invoke(SimpleHelloWorld $message)
    {
        $date = new \DateTime();
        echo sprintf("(%s): Salut %s, comment allez vous ?\n", $date->format('\L\e d/m/Y à H:i:s'), $message->getName());
    }
}
