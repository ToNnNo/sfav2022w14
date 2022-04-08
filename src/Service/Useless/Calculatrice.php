<?php

namespace App\Service\Useless;

class Calculatrice
{

    /**
     * @param array ...$values
     * @return float
     */
    public function addition(...$values): float
    {
        $result = 0;
        foreach ($values as $value) {
            if(is_int($value) || is_double($value)) {
                $result += $value;
            }
        }

        return $result;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function error(): void
    {
        throw new \Exception('Une erreur très grave !!');
    }

}
