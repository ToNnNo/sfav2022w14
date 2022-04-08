<?php

namespace App\Message;

final class SimpleHelloWorld
{

     private $name;

     public function __construct(string $name)
     {
         $this->name = $name;
     }

    public function getName(): string
    {
        return $this->name;
    }
}
