<?php

namespace App\MessageHandler;

use App\Message\Fail;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FailHandler implements MessageHandlerInterface
{
    public function __invoke(Fail $message)
    {
        echo "(Erreur): ".$message->getCause();
    }
}
