<?php

namespace App\Message;

final class Fail
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

     private $cause;

     public function __construct(string $cause)
     {
         $this->cause = $cause;
     }

    public function getCause(): string
    {
        return $this->cause;
    }
}
